<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:24
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\EventVenueRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class EventVenueCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class EventVenueCommandHandler implements MessageHandlerInterface
{
    /**
     * @var EventVenueRepository
     */
    protected $venueRepository;

    /**
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventVenueRepository $venueRepository)
    {
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
