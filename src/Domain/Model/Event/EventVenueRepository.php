<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-13
 * Time: 12:46
 */

namespace App\Domain\Model\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventVenueRepository
 *
 * @package App\Domain\Model\Event
 */
class EventVenueRepository extends ServiceEntityRepository
{
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
     * @param EventVenue $venue
     * @return EventVenue
     */
    public function save(EventVenue $venue): EventVenue
    {
        $this->_em->persist($venue);
        $this->_em->flush();
        return $venue;
    }

    /**
     * @param EventVenue $venue
     */
    public function delete(EventVenue $venue): void
    {
        $this->_em->remove($venue);
        $this->_em->flush();
    }
}
