<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:27
 */

namespace App\Application\Event\Application\Command;

use App\Application\Common\Command\IdAware;
use App\Application\Common\ContactPerson;
use App\Application\Event\Command\EventAware;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Class ApplyForTitleEvent
 *
 * @package App\Application\Event\Application\Command
 */
class ApplyForTitleEvent
{
    use IdAware, EventAware, TitleEventApplicationProperties;

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
        $this->proposedStartDate = new DateTimeImmutable('next Saturday');
        $this->proposedEndDate   = $this->proposedStartDate->modify('+ 1 day');
        $this->timeZone          = new DateTimeZone('Europe/Berlin');
    }
}
