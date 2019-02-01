<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:28
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\EventVenueRepository;
use App\Domain\Model\Event\TitleEventApplication;
use App\Domain\Model\Event\TitleEventApplicationRepository;

/**
 * Class TitleEventApplicationCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class TitleEventApplicationCommandHandler extends EventBasedCommandHandler
{
    /**
     * @var TitleEventApplicationRepository
     */
    protected $applicationRepository;

    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventRepository                 $eventRepository
     * @param TitleEventApplicationRepository $applicationRepository
     * @param EventVenueRepository            $venueRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        TitleEventApplicationRepository $applicationRepository,
        EventVenueRepository $venueRepository
    ) {
        parent::__construct($eventRepository);
        $this->applicationRepository = $applicationRepository;
        $this->venueRepository       = $venueRepository;
    }

    /**
     * @param string $id
     * @return TitleEventApplication
     */
    protected function getApplication(string $id): TitleEventApplication
    {
        $application = $this->applicationRepository->findById($id);
        if (!$application) {
            throw new \OutOfBoundsException('No title event application found for id ' . $id);
        }
        return $application;
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
