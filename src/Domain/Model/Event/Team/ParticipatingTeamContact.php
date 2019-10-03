<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 07:36
 */

namespace App\Domain\Model\Event\Team;

use App\Domain\Model\Common\ContactPerson;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;

/**
 * Class ParticipatingTeamContact
 *
 * @package App\Domain\Model\Event\Team
 *
 * @ORM\Entity()
 * @ORM\Table(name="event_team_contacts")
 */
class ParticipatingTeamContact extends ContactPerson
{
    use HasId, CreateTracking, UpdateTracking;

    /**
     * @param string           $id
     * @param string           $name
     * @param string           $email
     * @param PhoneNumber|null $phoneNumber
     */
    public function __construct(string $id, string $name, string $email, ?PhoneNumber $phoneNumber)
    {
        parent::__construct($name, $email, $phoneNumber);

        $this->setId($id)
             ->initCreateTracking()
             ->initUpdateTracking();
    }
}
