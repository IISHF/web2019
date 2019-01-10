<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:10
 */

namespace App\Domain\Model\Article;

use App\Domain\Common\Repository\DoctrinePaging;
use App\Domain\Model\Common\TagProvider;
use App\Domain\Model\File\FileRepository;
use App\Utils\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * Class ArticleRepository
 *
 * @package App\Domain\Model\Article
 */
class ArticleRepository extends ServiceEntityRepository implements TagProvider
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
        parent::__construct($managerRegistry, Article::class);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param string $id
     * @return Article|null
     */
    public function findById(string $id): ?Article
    {
        /** @var Article|null $article */
        $article = $this->find($id);
        return $article;
    }

    /**
     * @param string $slug
     * @return Article|null
     */
    public function findBySlug(string $slug): ?Article
    {
        /** @var Article|null $article */
        $article = $this->findOneBy(['slug' => $slug]);
        return $article;
    }

    /**
     * @param int $states
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|Article[]
     */
    public function findPaged(int $states = Article::STATE_PUBLISHED, int $page = 1, int $limit = 30): iterable
    {
        $stateFilter   = Article::getStates($states);
        $stateFilter[] = '';

        $queryBuilder = $this->createQueryBuilder('a')
                             ->addSelect('CASE WHEN a.publishedAt IS NULL THEN 0 ELSE 1 END AS HIDDEN notPublished')
                             ->andWhere('a.currentState IN (:states)')
                             ->setParameter('states', $stateFilter)
                             ->orderBy('notPublished', 'ASC ')
                             ->addOrderBy('a.publishedAt', 'DESC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function save(Article $article): Article
    {
        $this->_em->persist($article);
        $this->_em->flush();
        return $article;
    }

    /**
     * @param Article $article
     */
    public function delete(Article $article): void
    {
        $this->_em->remove($article);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableTags(): array
    {
        return Tags::createTagList(
            $this->createQueryBuilder('a')
                 ->select('a.tags')
                 ->getQuery()
                 ->getArrayResult()
        );
    }

    /**
     * @param Article $article
     * @return ArticleImages
     */
    public function findImages(Article $article): ArticleImages
    {
        if (!$article->isLegacyFormat()) {
            return ArticleImages::empty();
        }
        return new ArticleImages(
            $this->createAttachmentsQueryBuilder($article, ArticleImage::class)
                 ->getQuery()
                 ->getResult()
        );
    }

    /**
     * @param Article $article
     * @return ArticleDocuments
     */
    public function findDocuments(Article $article): ArticleDocuments
    {
        if (!$article->isLegacyFormat()) {
            return ArticleDocuments::empty();
        }
        return new ArticleDocuments(
            $this->createAttachmentsQueryBuilder($article, ArticleDocument::class)
                 ->getQuery()
                 ->getResult()
        );
    }

    /**
     * @param Article $article
     * @param string  $entityClass
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createAttachmentsQueryBuilder(Article $article, string $entityClass): \Doctrine\ORM\QueryBuilder
    {
        return $this->_em->createQueryBuilder()
                         ->select('a', 'f', 'fb')
                         ->from($entityClass, 'a')
                         ->innerJoin('a.file', 'f')
                         ->innerJoin('f.binary', 'fb')
                         ->where('a.article = :article')
                         ->setParameter('article', $article);
    }

    /**
     * @param ArticleAttachment $attachment
     * @return ArticleAttachment
     */
    public function saveAttachment(ArticleAttachment $attachment): ArticleAttachment
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->persist($attachment);
            $this->fileRepository->save($attachment->getFile(), false);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
        return $attachment;
    }

    /**
     * @param ArticleAttachment $attachment
     */
    public function deleteAttachment(ArticleAttachment $attachment): void
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->remove($attachment);
            $this->fileRepository->delete($attachment->getFile(), false);
            $this->_em->flush();
            $this->fileRepository->cleanupFileBinaries();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
    }
}
