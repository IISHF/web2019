<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 12:08
 */

namespace App\Domain\Model\Event\Team;

use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\Event;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class ParticipatingTeam
 *
 * @package App\Domain\Model\Event\Team
 *
 * @ORM\Entity(repositoryClass="ParticipatingTeamRepository")
 * @ORM\Table(
 *      name="event_teams",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_event_team", columns={"event_id", "name"})
 *      }
 * )
 */
class ParticipatingTeam
{
    use HasId, CreateTracking, UpdateTracking, AssociationOne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Event
     */
    private $event;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="ParticipatingTeamContact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=true)
     *
     * @var ParticipatingTeamContact|null
     */
    private $contact;

    /**
     * @param string $id
     * @param Event  $event
     * @param string $name
     */
    public function __construct(string $id, Event $event, string $name)
    {
        $this->setId($id)
             ->setEvent($event)
             ->setName($name)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        /** @var Event $event */
        $event = $this->getRelatedEntity($this->event, 'Participating team is not attached to an event.');
        return $event;
    }

    /**
     * @param Event $event
     * @return $this
     */
    private function setEvent(Event $event): self
    {
        return $this->setRelatedEntity($this->event, $event);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        Assert::lengthBetween($name, 1, 64);
        $this->name = $name;
        return $this;
    }

    /**
     * @return ParticipatingTeamContact|null
     */
    public function getContact(): ?ParticipatingTeamContact
    {
        return $this->contact;
    }

    /**
     * @param ParticipatingTeamContact|null $contact
     * @return $this
     */
    public function setContact(?ParticipatingTeamContact $contact): self
    {
        return $this->setRelatedEntity($this->contact, $contact);
    }
}
