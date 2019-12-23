<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:15
 */

namespace App\Domain\Model\HallOfFame;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class HallOfFameRepository
 *
 * @package App\Domain\Model\HallOfFame
 */
class HallOfFameRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, HallOfFameEntry::class);
    }

    /**
     * @param string $id
     * @return HallOfFameEntry|null
     */
    public function findById(string $id): ?HallOfFameEntry
    {
        /** @var HallOfFameEntry|null $entry */
        $entry = $this->find($id);
        return $entry;
    }

    /**
     * @return iterable|HallOfFameEntry[]
     */
    public function findAll(): iterable
    {
        return $this->createQueryBuilder('h')
                    ->orderBy('h.season', 'DESC')
                    ->addOrderBy('h.championship', 'DESC')
                    ->addOrderBy('h.ageGroup', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return iterable|HallOfFameEntry[][]
     */
    public function groupBySeason(): iterable
    {
        return $this->groupBy(
            $this->createQueryBuilder('h')
                 ->orderBy('h.season', 'DESC')
                 ->addOrderBy('h.championship', 'DESC')
                 ->addOrderBy('h.ageGroup', 'ASC'),
            function (HallOfFameEntry $entry) {
                return $entry->getSeason();
            }
        );
    }

    /**
     * @return iterable|HallOfFameEntry[][]
     */
    public function groupByAgeGroup(): iterable
    {
        return $this->groupBy(
            $this->createQueryBuilder('h')
                 ->orderBy('h.ageGroup', 'ASC')
                 ->addOrderBy('h.season', 'DESC')
                 ->addOrderBy('h.championship', 'DESC'),
            function (HallOfFameEntry $entry) {
                return $entry->getAgeGroupName();
            }
        );
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param callable                   $getGroup
     * @return iterable|HallOfFameEntry[][]
     */
    private function groupBy(QueryBuilder $queryBuilder, callable $getGroup): iterable
    {
        $current = null;
        $batch   = [];
        foreach ($queryBuilder->getQuery()->getResult() as $entry) {
            $groupBy = $getGroup($entry);
            if ($current !== $groupBy) {
                if (!empty($batch)) {
                    yield $current => $batch;
                }
                $current = $groupBy;
                $batch   = [];
            }
            $batch[] = $entry;
        }
        if (!empty($batch)) {
            yield $current => $batch;
        }
    }

    /**
     * @param HallOfFameEntry $entry
     * @return HallOfFameEntry
     */
    public function save(HallOfFameEntry $entry): HallOfFameEntry
    {
        $this->_em->persist($entry);
        return $entry;
    }

    /**
     * @param HallOfFameEntry $entry
     */
    public function delete(HallOfFameEntry $entry): void
    {
        $this->_em->remove($entry);
    }
}
