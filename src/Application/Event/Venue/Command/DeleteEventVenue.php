<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:09
 */

namespace App\Application\Event\Venue\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Venue\EventVenue;

/**
 * Class DeleteEventVenue
 *
 * @package App\Application\Event\Venue\Command
 */
class DeleteEventVenue
{
    use UuidAware;

    /**
     * @param EventVenue $venue
     * @return self
     */
    public static function delete(EventVenue $venue): self
    {
        return new self($venue->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
