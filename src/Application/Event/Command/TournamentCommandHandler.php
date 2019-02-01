<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 07:26
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\EventVenueRepository;

/**
 * Class TournamentCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class TournamentCommandHandler extends EventCommandHandler
{
    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventRepository      $eventRepository
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventRepository $eventRepository, EventVenueRepository $venueRepository)
    {
        parent::__construct($eventRepository);
        $this->venueRepository = $venueRepository;
    }

    /**
     * @param string $id
     * @return EventVenue
     */
    protected function getVenue(string $id): EventVenue
    {
        $venue = $this->venueRepository->findById($id);
        if (!$venue) {
            throw new \OutOfBoundsException('No event venue found for id ' . $id);
        }
        return $venue;
    }
}