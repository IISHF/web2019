<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:15
 */

namespace App\Domain\Model\HallOfFame;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
                    ->addOrderBy('h.ageGroup', 'ASC')
                    ->getQuery()
                    ->getResult();
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
