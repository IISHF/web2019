<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 10:58
 */

namespace App\Domain\Common\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Trait DoctrinePaging
 *
 * @package App\Domain\Common\Repository
 */
trait DoctrinePaging
{
    /**
     * @param Query|QueryBuilder $query
     * @param int                $page
     * @param int                $limit
     * @return Pagerfanta
     */
    public function createPager($query, int $page = 1, int $limit = 30): Pagerfanta
    {
        $pager = new Pagerfanta(new QueryAdapter($query));
        $pager->setCurrentPage($page)
              ->setMaxPerPage($limit);
        return $pager;
    }
}
