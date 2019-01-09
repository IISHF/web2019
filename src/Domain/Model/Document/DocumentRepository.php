<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:06
 */

namespace App\Domain\Model\Document;

use App\Domain\Common\Repository\DoctrinePaging;
use App\Domain\Model\File\FileRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * Class DocumentRepository
 *
 * @package App\Domain\Model\Document
 */
class DocumentRepository extends ServiceEntityRepository
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
        $this->_em->persist($document);
        $this->_em->flush();
        return $document;
    }

    /**
     * @param Document $document
     */
    public function delete(Document $document): void
    {
        $this->_em->remove($document);
        $this->_em->flush();
    }

    /**
     * @return string[]
     */
    public function findAvailableTags(): iterable
    {
        $tags = array_keys(
            array_reduce(
                array_column(
                    $this->createQueryBuilder('d')
                         ->select('d.tags')
                         ->getQuery()
                         ->getArrayResult(),
                    'tags'
                ),
                function (array $carry, array $tags) {
                    foreach ($tags as $tag) {
                        $carry[$tag] = true;
                    }
                    return $carry;
                },
                []
            )
        );

        $collator = new \Collator('en_GB');
        $collator->sort($tags, \Collator::SORT_STRING);

        return $tags;
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
