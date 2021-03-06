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
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
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
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|Article[]
     */
    public function findAllPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilder('a')
                             ->addSelect('CASE WHEN a.publishedAt IS NULL THEN 0 ELSE 1 END AS HIDDEN published')
                             ->orderBy('published', 'ASC ')
                             ->addOrderBy('a.publishedAt', 'DESC')
                             ->addOrderBy('a.updatedAt', 'DESC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param int                    $page
     * @param int                    $limit
     * @param DateTimeImmutable|null $date
     * @return iterable|Pagerfanta|Article[]
     */
    public function findPublishedPaged(int $page = 1, int $limit = 30, ?DateTimeImmutable $date = null): iterable
    {
        $queryBuilder = $this->createPublishedQueryBuilder($date);
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param int                    $year
     * @param int                    $page
     * @param int                    $limit
     * @param DateTimeImmutable|null $date
     * @return iterable|Pagerfanta|Article[]
     */
    public function findPublishedInYearPaged(
        int $year,
        int $page = 1,
        int $limit = 30,
        ?DateTimeImmutable $date = null
    ): iterable {
        $start = new DateTimeImmutable($year . '-01-01 00:00:00');
        $end   = new DateTimeImmutable(($year + 1) . '-01-01 00:00:00');

        $queryBuilder = $this->createPublishedQueryBuilder($date)
                             ->andWhere('a.publishedAt >= :start')
                             ->andWhere('a.publishedAt < :end')
                             ->setParameter('start', $start, 'datetime_immutable')
                             ->setParameter('end', $end, 'datetime_immutable');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @return int[]
     */
    public function findPublishingYears(): array
    {
        $connection   = $this->_em->getConnection();
        $queryBuilder = $connection->createQueryBuilder()
                                   ->select('YEAR(a.published_at) AS y')
                                   ->distinct()
                                   ->from('articles', 'a')
                                   ->orderBy('y', 'DESC');
        return array_map('intval', $connection->fetchFirstColumn($queryBuilder->getSQL()));
    }

    /**
     * @param int                    $limit
     * @param DateTimeImmutable|null $date
     * @return Article[]
     */
    public function findMostRecent(int $limit = 10, ?DateTimeImmutable $date = null): iterable
    {
        return $this->createPublishedQueryBuilder($date)
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return QueryBuilder
     */
    private function createPublishedQueryBuilder(?DateTimeImmutable $date = null): QueryBuilder
    {
        $date = $date ?? new DateTimeImmutable('now');
        return $this->createQueryBuilder('a')
                    ->where('a.currentState = :state')
                    ->andWhere('a.publishedAt IS NOT NULL')
                    ->andWhere('a.publishedAt <= :date')
                    ->setParameter('state', Article::STATE_PUBLISHED)
                    ->setParameter('date', $date, 'datetime_immutable')
                    ->addOrderBy('a.publishedAt', 'DESC');
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function save(Article $article): Article
    {
        $this->_em->persist($article);
        return $article;
    }

    /**
     * @param Article $article
     */
    public function delete(Article $article): void
    {
        $this->_em->remove($article);
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
     * @return QueryBuilder
     */
    private function createAttachmentsQueryBuilder(Article $article, string $entityClass): QueryBuilder
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
        $this->_em->persist($attachment);
        $this->fileRepository->save($attachment->getFile());
        return $attachment;
    }

    /**
     * @param ArticleAttachment $attachment
     */
    public function deleteAttachment(ArticleAttachment $attachment): void
    {
        $this->_em->remove($attachment);
        $this->fileRepository->delete($attachment->getFile());
    }
}
