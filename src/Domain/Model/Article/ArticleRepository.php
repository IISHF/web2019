<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:10
 */

namespace App\Domain\Model\Article;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class ArticleRepository
 *
 * @package App\Domain\Model\Article
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Article::class);
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
                             ->andWhere('a.currentState IN (:states)')
                             ->setParameter('states', $stateFilter)
                             ->orderBy('a.publishedAt', 'DESC');
        $pager        = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager->setCurrentPage($page)
              ->setMaxPerPage($limit);
        return $pager;
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
     * @return string[]
     */
    public function findAvailableTags(): iterable
    {
        $tags = array_keys(
            array_reduce(
                array_column(
                    $this->createQueryBuilder('a')
                         ->select('a.tags')
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
}
