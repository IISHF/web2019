<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:12
 */

namespace App\Application\Event\Team\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Common\ContactPerson;
use App\Application\Event\Command\EventAware;

/**
 * Class AddParticipatingTeam
 *
 * @package App\Application\Event\Team\Command
 */
class AddParticipatingTeam
{
    use UuidAware, EventAware, ParticipatingTeamProperties;

    /**
     * @param string $eventId
     * @return self
     */
    public static function add(string $eventId): self
    {
        return new self(self::createUuid(), $eventId);
    }

    /**
     * @param string $id
     * @param string $eventId
     */
    private function __construct(string $id, string $eventId)
    {
        $this->id      = $id;
        $this->eventId = $eventId;
        $this->contact = new ContactPerson();
    }
}
