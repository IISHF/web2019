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
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="participants")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Event|null
     */
    private $event;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @param string $id
     * @param Event  $event
     * @param string $name
     */
    public function __construct(string $id, Event $event, string $name)
    {
        Assert::uuid($id);

        $this->id = $id;

        $this->setEvent($event)
             ->setName($name)
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
        if (!$this->event) {
            throw new \BadMethodCallException('Participating team is not attached to an event.');
        }
        return $this->event;
    }

    /**
     * @internal
     *
     * @param Event|null $event
     * @return $this
     */
    public function setEvent(?Event $event): self
    {
        if ($event === $this->event) {
            return $this;
        }

        if ($this->event) {
            $previousEvent = $this->event;
            $this->event   = null;
            $previousEvent->removeParticipant($this);
        }
        if ($event) {
            $this->event = $event;
            $event->addParticipant($this);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromEvent(): self
    {
        return $this->setEvent(null);
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
}
