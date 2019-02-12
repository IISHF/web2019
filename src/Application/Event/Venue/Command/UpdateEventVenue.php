<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:04
 */

namespace App\Application\Event\Venue\Command;

use App\Application\Common\Address;
use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Venue\EventVenue;

/**
 * Class UpdateEventVenue
 *
 * @package App\Application\Event\Venue\Command
 */
class UpdateEventVenue
{
    use UuidAware, EventVenueProperties;

    /**
     * @param EventVenue $venue
     * @return self
     */
    public static function update(EventVenue $venue): self
    {
        return new self(
            $venue->getId(),
            $venue->getName(),
            Address::fromAddressEntity($venue->getAddress()),
            $venue->getRinkInfo()
        );
    }

    /**
     * @param string      $id
     * @param string      $name
     * @param Address     $address
     * @param string|null $rinkInfo
     */
    private function __construct(string $id, string $name, Address $address, ?string $rinkInfo)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->address  = $address;
        $this->rinkInfo = $rinkInfo;
    }
}
