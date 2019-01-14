<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 08:57
 */

namespace App\Application\Event\Command;

use App\Application\Common\Address;
use App\Application\Common\Command\UuidAware;

/**
 * Class CreateEventVenue
 *
 * @package App\Application\Event\Command
 */
class CreateEventVenue
{
    use UuidAware, EventVenueProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id      = $id;
        $this->address = new Address();
    }
}
