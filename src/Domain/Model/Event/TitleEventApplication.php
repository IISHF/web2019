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
 * @ORM\Table(name="event_applications")
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
     * @ORM\ManyToOne(targetEntity="TitleEvent")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var TitleEvent
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
     * @ORM\ManyToOne(targetEntity="EventVenue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     *
     * @var EventVenue
     */
    private $venue;

    /**
     * @param string             $id
     * @param TitleEvent         $titleEvent
     * @param EventContact       $contact
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     * @param EventVenue         $venue
     */
    public function __construct(
        string $id,
        TitleEvent $titleEvent,
        EventContact $contact,
        \DateTimeImmutable $proposedStartDate,
        \DateTimeImmutable $proposedEndDate,
        EventVenue $venue
    ) {
        Assert::uuid($id);

        $this->id         = $id;
        $this->titleEvent = $titleEvent;

        $this->setContact($contact)
             ->setProposedDate($proposedStartDate, $proposedEndDate)
             ->setVenue($venue)
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
        return $this->titleEvent;
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

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue
    {
        return $this->venue;
    }

    /**
     * @param EventVenue $venue
     * @return $this
     */
    public function setVenue(EventVenue $venue): self
    {
        $this->venue = $venue;
        return $this;
    }
}
