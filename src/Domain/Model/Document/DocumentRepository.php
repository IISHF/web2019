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
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
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
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|Document[]
     */
    public function findPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilder('d')
                             ->orderBy('d.title', 'ASC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param Document $document
     * @return Document
     */
    public function save(Document $document): Document
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->persist($document);
            foreach ($document->getVersions() as $version) {
                $this->fileRepository->save($version->getFile());
                $this->_em->persist($version);
            }
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
        return $document;
    }

    /**
     * @param Document $document
     */
    public function delete(Document $document): void
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->remove($document);
            foreach ($document->getVersions() as $version) {
                $this->fileRepository->delete($version->getFile());
                $this->_em->remove($version);
            }
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
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
     * @param string $id
     * @return DocumentVersion|null
     */
    public function findVersionById(string $id): ?DocumentVersion
    {
        /** @var DocumentVersion|null $version */
        $version = $this->createQueryBuilder('dv')
                        ->addSelect('d')
                        ->join('dv.document', 'd')
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
        $version = $this->createQueryBuilder('dv')
                        ->addSelect('d')
                        ->join('dv.document', 'd')
                        ->where('dv.slug = :versionSlug')
                        ->andWhere('d.slug = :documentSlug')
                        ->setParameter('versionSlug', $versionSlug)
                        ->setParameter('documentSlug', $documentSlug)
                        ->getQuery()
                        ->getOneOrNullResult();
        return $version;
    }

    /**
     * @param DocumentVersion $documentVersion
     * @return DocumentVersion
     */
    public function saveVersion(DocumentVersion $documentVersion): DocumentVersion
    {
        $this->_em->beginTransaction();
        try {
            $this->fileRepository->save($documentVersion->getFile());
            $this->_em->persist($documentVersion);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
        return $documentVersion;
    }

    /**
     * @param DocumentVersion $documentVersion
     */
    public function deleteVersion(DocumentVersion $documentVersion): void
    {
        $this->_em->beginTransaction();
        try {
            $this->fileRepository->delete($documentVersion->getFile());
            $this->_em->remove($documentVersion);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
    }
}
