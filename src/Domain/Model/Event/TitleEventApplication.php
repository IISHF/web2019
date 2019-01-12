<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:49
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class TitleEventApplication
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 * @ORM\Table(name="event_applicant")
 */
class TitleEventApplication
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
     * @ORM\ManyToOne(targetEntity="TitleEvent", inversedBy="applications")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var TitleEvent|null
     */
    private $titleEvent;

    /**
     * @ORM\Embedded(class="EventContact", columnPrefix="contact_")
     *
     * @var EventContact
     */
    private $contact;

    /**
     * @ORM\Column(name="proposed_start_date", type="date_immutable")
     *
     * @var \DateTimeImmutable
     */
    private $proposedStartDate;

    /**
     * @ORM\Column(name="proposed_end_date", type="date_immutable")
     *
     * @var \DateTimeImmutable
     */
    private $proposedEndDate;

    /**
     * @param string             $id
     * @param TitleEvent         $titleEvent
     * @param EventContact       $contact
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     */
    public function __construct(
        string $id,
        TitleEvent $titleEvent,
        EventContact $contact,
        \DateTimeImmutable $proposedStartDate,
        \DateTimeImmutable $proposedEndDate
    ) {
        Assert::uuid($id);

        $this->id = $id;

        $this->setTitleEvent($titleEvent)
             ->setContact($contact)
             ->setProposedDate($proposedStartDate, $proposedEndDate)
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
     * @return TitleEvent
     */
    public function getTitleEvent(): TitleEvent
    {
        if (!$this->titleEvent) {
            throw new \BadMethodCallException('Title event applicant is not attached to a title event.');
        }
        return $this->titleEvent;
    }

    /**
     * @internal
     *
     * @param TitleEvent|null $titleEvent
     * @return $this
     */
    public function setTitleEvent(?TitleEvent $titleEvent): self
    {
        if ($titleEvent === $this->titleEvent) {
            return $this;
        }

        if ($this->titleEvent) {
            $previousTitleEvent = $this->titleEvent;
            $this->titleEvent   = null;
            $previousTitleEvent->removeApplication($this);
        }
        if ($titleEvent) {
            $this->titleEvent = $titleEvent;
            $titleEvent->addApplication($this);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromTitleEvent(): self
    {
        return $this->setTitleEvent(null);
    }

    /**
     * @return EventContact
     */
    public function getContact(): EventContact
    {
        return $this->contact;
    }

    /**
     * @param EventContact $contact
     * @return $this
     */
    public function setContact(EventContact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getProposedStartDate(): \DateTimeImmutable
    {
        return $this->proposedStartDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getProposedEndDate(): \DateTimeImmutable
    {
        return $this->proposedEndDate;
    }

    /**
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     * @return $this
     */
    public function setProposedDate(\DateTimeImmutable $proposedStartDate, \DateTimeImmutable $proposedEndDate): self
    {
        Assert::lessThanEq($proposedStartDate, $proposedEndDate);
        Assert::greaterThanEq($proposedEndDate, $proposedStartDate);
        $this->proposedStartDate = $proposedStartDate;
        $this->proposedEndDate   = $proposedEndDate;
        return $this;
    }
}
