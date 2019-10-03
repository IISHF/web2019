<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 08:57
 */

namespace App\Application\Event\Venue\Command;

use App\Application\Common\Address;
use App\Application\Common\Command\IdAware;

/**
 * Class CreateEventVenue
 *
 * @package App\Application\Event\Venue\Command
 */
class CreateEventVenue
{
    use IdAware, EventVenueProperties;

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
