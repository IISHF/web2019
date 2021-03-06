<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:06
 */

namespace App\Domain\Model\Document;

use App\Domain\Common\Repository\DoctrinePaging;
use App\Domain\Model\Common\TagProvider;
use App\Domain\Model\File\FileRepository;
use App\Utils\Tags;
use Collator;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * Class DocumentRepository
 *
 * @package App\Domain\Model\Document
 */
class DocumentRepository extends ServiceEntityRepository implements TagProvider
{
    use DoctrinePaging;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param FileRepository  $fileRepository
     */
    public function __construct(ManagerRegistry $managerRegistry, FileRepository $fileRepository)
    {
        parent::__construct($managerRegistry, Document::class);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param string $id
     * @return Document|null
     */
    public function findById(string $id): ?Document
    {
        /** @var Document|null $document */
        $document = $this->findOneBy(['id' => $id]);
        return $document;
    }

    /**
     * @param string $slug
     * @return Document|null
     */
    public function findBySlug(string $slug): ?Document
    {
        /** @var Document|null $document */
        $document = $this->findOneBy(['slug' => $slug]);
        return $document;
    }

    /**
     * @param string $title
     * @return Document|null
     */
    public function findByTitle(string $title): ?Document
    {
        /** @var Document|null $document */
        $document = $this->findOneBy(['title' => $title]);
        return $document;
    }

    /**
     * @param string $id
     * @return Document|null
     */
    public function findByIdWithVersions(string $id): ?Document
    {
        /** @var Document|null $document */
        $document = $this->createQueryBuilderWithVersions()
                         ->where('d.id = :id')
                         ->setParameter('id', $id)
                         ->getQuery()
                         ->getOneOrNullResult();
        return $document;
    }

    /**
     * @param string $slug
     * @return Document|null
     */
    public function findBySlugWithVersions(string $slug): ?Document
    {
        /** @var Document|null $document */
        $document = $this->createQueryBuilderWithVersions()
                         ->where('d.slug = :slug')
                         ->setParameter('slug', $slug)
                         ->getQuery()
                         ->getOneOrNullResult();
        return $document;
    }

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilderWithVersions(): QueryBuilder
    {
        return $this->createQueryBuilder('d')
                    ->addSelect('dv', 'f')
                    ->leftJoin('d.versions', 'dv')
                    ->leftJoin('dv.file', 'f');
    }

    /**
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|Document[]
     */
    public function findPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilderWithVersions()
                             ->orderBy('d.title', 'ASC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param DateTimeImmutable|null $reference
     * @return Document[]
     */
    public function findCurrentlyValid(?DateTimeImmutable $reference = null): array
    {
        $reference = $reference ?? new DateTimeImmutable('now');
        $documents = $this->createQueryBuilderWithVersions()
                          ->where(
                              'EXISTS (SELECT v.id FROM ' . DocumentVersion::class . ' AS v WHERE v.document = d AND (v.validFrom IS NULL OR v.validFrom <= :date) AND (v.validUntil IS NULL OR v.validUntil >= :date))'
                          )
                          ->setParameter('date', $reference, 'datetime_immutable')
                          ->orderBy('d.title', 'ASC')
                          ->getQuery()
                          ->getResult();

        $collator = Collator::create('en-US');
        usort(
            $documents,
            static function (Document $a, Document $b) use ($collator): int {
                $aIndex = $a->getSortOrder();
                $bIndex = $b->getSortOrder();
                if ($aIndex === $bIndex) {
                    return $collator->compare($a->getTitle(), $b->getTitle());
                }
                return $aIndex - $bIndex;
            }
        );
        return $documents;
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function save(Document $document): Document
    {
        $this->_em->persist($document);
        foreach ($document->getVersions() as $version) {
            $this->_em->persist($version);
            $this->fileRepository->save($version->getFile());
        }
        return $document;
    }

    /**
     * @param Document $document
     */
    public function delete(Document $document): void
    {
        $this->_em->remove($document);
        foreach ($document->getVersions() as $version) {
            $this->_em->remove($version);
            $this->fileRepository->delete($version->getFile());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableTags(): array
    {
        return Tags::createTagList(
            $this->createQueryBuilder('d')
                 ->select('d.tags')
                 ->getQuery()
                 ->getArrayResult()
        );
    }

    /**
     * @param string $documentId
     * @param string $documentVersion
     * @return DocumentVersion|null
     */
    public function findVersion(string $documentId, string $documentVersion): ?DocumentVersion
    {
        /** @var DocumentVersion|null $version */
        $version = $this->createVersionQueryBuilder()
                        ->where('dv.version = :version')
                        ->andWhere('d.id = :documentId')
                        ->setParameter('version', $documentVersion)
                        ->setParameter('documentId', $documentId)
                        ->getQuery()
                        ->getOneOrNullResult();
        return $version;
    }

    /**
     * @param string $id
     * @return DocumentVersion|null
     */
    public function findVersionById(string $id): ?DocumentVersion
    {
        /** @var DocumentVersion|null $version */
        $version = $this->createVersionQueryBuilder()
                        ->where('dv.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery()
                        ->getOneOrNullResult();
        return $version;
    }

    /**
     * @param string $documentSlug
     * @param string $versionSlug
     * @return DocumentVersion|null
     */
    public function findVersionBySlug(string $documentSlug, string $versionSlug): ?DocumentVersion
    {
        /** @var DocumentVersion|null $version */
        $version = $this->createVersionQueryBuilder()
                        ->where('dv.slug = :versionSlug')
                        ->andWhere('d.slug = :documentSlug')
                        ->setParameter('versionSlug', $versionSlug)
                        ->setParameter('documentSlug', $documentSlug)
                        ->getQuery()
                        ->getOneOrNullResult();
        return $version;
    }

    /**
     * @return QueryBuilder
     */
    private function createVersionQueryBuilder(): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
                         ->select('dv', 'd', 'f')
                         ->from(DocumentVersion::class, 'dv')
                         ->join('dv.document', 'd')
                         ->join('dv.file', 'f');
    }

    /**
     * @param DocumentVersion $documentVersion
     * @return DocumentVersion
     */
    public function saveVersion(DocumentVersion $documentVersion): DocumentVersion
    {
        $this->_em->persist($documentVersion);
        $this->fileRepository->save($documentVersion->getFile());
        return $documentVersion;
    }

    /**
     * @param DocumentVersion $documentVersion
     */
    public function deleteVersion(DocumentVersion $documentVersion): void
    {
        $documentVersion->removeFromDocument();
        $this->_em->remove($documentVersion);
        $this->fileRepository->delete($documentVersion->getFile());
    }
}
