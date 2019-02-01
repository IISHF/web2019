<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:28
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventRepository;
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
     * @param EventRepository                 $eventRepository
     * @param EventVenueRepository            $venueRepository
     * @param TitleEventApplicationRepository $applicationRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        EventVenueRepository $venueRepository,
        TitleEventApplicationRepository $applicationRepository
    ) {
        parent::__construct($eventRepository, $venueRepository);
        $this->applicationRepository = $applicationRepository;
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
}
