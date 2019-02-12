<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:51
 */

namespace App\Application\Event\Venue\Command;

use App\Domain\Model\Event\Venue\EventVenue;

/**
 * Class CreateEventVenueHandler
 *
 * @package App\Application\Event\Venue\Command
 */
class CreateEventVenueHandler extends EventVenueCommandHandler
{
    /**
     * @param CreateEventVenue $command
     */
    public function __invoke(CreateEventVenue $command): void
    {
        $address = $command->getAddress();
        $venue   = new EventVenue(
            $command->getId(),
            $command->getName(),
            $address->getAddress1(),
            $address->getAddress2(),
            $address->getState(),
            $address->getPostalCode(),
            $address->getCity(),
            $address->getCountry(),
            $command->getRinkInfo()
        );
        $this->venueRepository->save($venue);
    }
}
