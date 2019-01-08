<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 10:58
 */

namespace App\Domain\Common\Repository;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Trait DoctrinePaging
 *
 * @package App\Domain\Common\Repository
 */
trait DoctrinePaging
{
    /**
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query
     * @param int                                            $page
     * @param int                                            $limit
     * @return Pagerfanta
     */
    public function createPager($query, int $page = 1, int $limit = 30): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($query));
        $pager->setCurrentPage($page)
              ->setMaxPerPage($limit);
        return $pager;
    }
}
