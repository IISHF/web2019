<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 12:08
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class ParticipatingTeam
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity(repositoryClass="ParticipatingTeamRepository")
 * @ORM\Table(name="event_teams")
 */
class ParticipatingTeam
{
    use CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Event")
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
        Assert::uuid($id);

        $this->id    = $id;
        $this->event = $event;

        $this->setName($name)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
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
    public function setHost(?ParticipatingTeamContact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }
}