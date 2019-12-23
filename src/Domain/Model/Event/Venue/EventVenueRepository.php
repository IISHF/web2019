<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-13
 * Time: 12:46
 */

namespace App\Domain\Model\Event\Venue;

use App\Domain\Common\Repository\DoctrinePaging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * Class EventVenueRepository
 *
 * @package App\Domain\Model\Event\Venue
 */
class EventVenueRepository extends ServiceEntityRepository
{
    use DoctrinePaging;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, EventVenue::class);
    }

    /**
     * @param string $id
     * @return EventVenue|null
     */
    public function findById(string $id): ?EventVenue
    {
        /** @var EventVenue|null $venue */
        $venue = $this->find($id);
        return $venue;
    }

    /**
     * @param string $name
     * @return EventVenue|null
     */
    public function findByName(string $name): ?EventVenue
    {
        /** @var EventVenue|null $venue */
        $venue = $this->findOneBy(['name' => $name]);
        return $venue;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|EventVenue[]
     */
    public function findPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilder('v')
                             ->orderBy('v.name', 'ASC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @return iterable|EventVenue[]
     */
    public function findAll(): iterable
    {
        return $this->createQueryBuilder('v')
                    ->orderBy('v.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param EventVenue $venue
     * @return EventVenue
     */
    public function save(EventVenue $venue): EventVenue
    {
        $this->_em->persist($venue);
        return $venue;
    }

    /**
     * @param EventVenue $venue
     */
    public function delete(EventVenue $venue): void
    {
        $this->_em->remove($venue);
    }
}
