<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:12
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Common\ContactPerson;
use App\Domain\Model\Event\ParticipatingTeam;

/**
 * Class UpdateParticipatingTeam
 *
 * @package App\Application\Event\Command
 */
class UpdateParticipatingTeam
{
    use UuidAware, ParticipatingTeamProperties;

    /**
     * @param ParticipatingTeam $team
     * @return self
     */
    public static function update(ParticipatingTeam $team): self
    {
        $contact = $team->getContact();
        return new self(
            $team->getId(),
            $team->getName(),
            $contact ? ContactPerson::update($contact) : new ContactPerson()
        );
    }

    /**
     * @param string        $id
     * @param string        $name
     * @param ContactPerson $contact
     */
    private function __construct(string $id, string $name, ContactPerson $contact)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->contact = $contact;
    }
}
