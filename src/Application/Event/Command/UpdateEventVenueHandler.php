<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:04
 */

namespace App\Application\Event\Command;

/**
 * Class UpdateEventVenueHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateEventVenueHandler extends EventVenueCommandHandler
{
    /**
     * @param UpdateEventVenue $command
     */
    public function __invoke(UpdateEventVenue $command): void
    {
        $address = $command->getAddress();
        $venue   = $this->getVenue($command->getId());
        $venue->setName($command->getName())
              ->setRinkInfo($command->getRinkInfo());
        $venue->getAddress()
              ->setAddress($address->getAddress1(), $address->getAddress2())
              ->setState($address->getState())
              ->setPostalCode($address->getPostalCode())
              ->setCity($address->getCity())
              ->setCountry($address->getCountry());
        $this->venueRepository->save($venue);
    }
}
