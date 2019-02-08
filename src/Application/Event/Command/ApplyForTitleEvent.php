<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:27
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Common\ContactPerson;

/**
 * Class ApplyForTitleEvent
 *
 * @package App\Application\Event\Command
 */
class ApplyForTitleEvent
{
    use UuidAware, EventAware, TitleEventApplicationProperties;

    /**
     * @param string $eventId
     * @return self
     */
    public static function apply(string $eventId): self
    {
        return new self(self::createUuid(), $eventId);
    }

    /**
     * @param string $id
     * @param string $eventId
     */
    private function __construct(string $id, string $eventId)
    {
        $this->id                = $id;
        $this->eventId           = $eventId;
        $this->contact           = new ContactPerson();
        $this->proposedStartDate = new \DateTimeImmutable('next Saturday');
        $this->proposedEndDate   = $this->proposedStartDate->modify('+ 1 day');
        $this->timeZone          = new \DateTimeZone('Europe/Berlin');
    }
}
