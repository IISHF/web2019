<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:58
 */

namespace App\Application\Event\Command;

/**
 * Class DeleteEventVenueHandler
 *
 * @package App\Application\Event\Command
 */
class DeleteEventVenueHandler extends EventVenueCommandHandler
{
    /**
     * @param DeleteEventVenue $command
     */
    public function __invoke(DeleteEventVenue $command): void
    {
        $venue = $this->getVenue($command->getId());
        $this->venueRepository->delete($venue);
    }
}
