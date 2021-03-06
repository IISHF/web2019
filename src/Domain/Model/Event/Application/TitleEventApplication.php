<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:49
 */

namespace App\Domain\Model\Event\Application;

use App\Domain\Common\Timezone;
use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\ContactPerson;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\TitleEvent;
use App\Domain\Model\Event\Venue\EventVenue;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class TitleEventApplication
 *
 * @package App\Domain\Model\Event\Application
 *
 * @ORM\Entity(repositoryClass="TitleEventApplicationRepository")
 * @ORM\Table(name="event_applications")
 */
class TitleEventApplication
{
    use HasId, CreateTracking, UpdateTracking, AssociationOne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\TitleEvent")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var TitleEvent
     */
    private $titleEvent;

    /**
     * @ORM\Column(name="applicant_club", type="string", length=128)
     *
     * @var string
     */
    private $applicantClub;

    /**
     * @ORM\Embedded(class="App\Domain\Model\Common\ContactPerson", columnPrefix="contact_")
     *
     * @var ContactPerson
     */
    private $contact;

    /**
     * @ORM\Column(name="proposed_start_date", type="date_immutable")
     *
     * @var DateTimeImmutable
     */
    private $proposedStartDate;

    /**
     * @ORM\Column(name="proposed_end_date", type="date_immutable")
     *
     * @var DateTimeImmutable
     */
    private $proposedEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Venue\EventVenue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id")
     *
     * @var EventVenue
     */
    private $venue;

    /**
     * @ORM\Column(name="time_zone", type="string", length=32, nullable=true)
     *
     * @var string|null
     */
    private $timeZone;

    /**
     * @var DateTimeZone|null
     */
    private $timeZoneInstance;

    /**
     * @param string             $id
     * @param TitleEvent         $titleEvent
     * @param string             $applicantClub
     * @param ContactPerson      $contact
     * @param DateTimeImmutable $proposedStartDate
     * @param DateTimeImmutable $proposedEndDate
     * @param EventVenue         $venue
     * @param DateTimeZone      $timeZone
     */
    public function __construct(
        string $id,
        TitleEvent $titleEvent,
        string $applicantClub,
        ContactPerson $contact,
        DateTimeImmutable $proposedStartDate,
        DateTimeImmutable $proposedEndDate,
        EventVenue $venue,
        DateTimeZone $timeZone
    ) {
        $this->setId($id)
             ->setTitleEvent($titleEvent)
             ->setApplicantClub($applicantClub)
             ->setContact($contact)
             ->setProposedDate($proposedStartDate, $proposedEndDate, $timeZone)
             ->setVenue($venue)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return TitleEvent
     */
    public function getTitleEvent(): TitleEvent
    {
        /** @var TitleEvent $titleEvent */
        $titleEvent = $this->getRelatedEntity($this->titleEvent, 'Event application is not attached to a title event.');
        return $titleEvent;
    }

    /**
     * @param TitleEvent $titleEvent
     * @return $this
     */
    private function setTitleEvent(TitleEvent $titleEvent): self
    {
        return $this->setRelatedEntity($this->titleEvent, $titleEvent);
    }

    /**
     * @return string
     */
    public function getApplicantClub(): string
    {
        return $this->applicantClub;
    }

    /**
     * @param string $applicantClub
     * @return $this
     */
    public function setApplicantClub(string $applicantClub): self
    {
        Assert::lengthBetween($applicantClub, 1, 128);
        $this->applicantClub = $applicantClub;
        return $this;
    }

    /**
     * @return ContactPerson
     */
    public function getContact(): ContactPerson
    {
        return $this->contact;
    }

    /**
     * @param ContactPerson $contact
     * @return $this
     */
    public function setContact(ContactPerson $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getProposedStartDate(): DateTimeImmutable
    {
        return $this->proposedStartDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getProposedEndDate(): DateTimeImmutable
    {
        return $this->proposedEndDate;
    }

    /**
     * @param DateTimeImmutable $proposedStartDate
     * @param DateTimeImmutable $proposedEndDate
     * @param DateTimeZone      $timeZone
     * @return $this
     */
    public function setProposedDate(
        DateTimeImmutable $proposedStartDate,
        DateTimeImmutable $proposedEndDate,
        DateTimeZone $timeZone
    ): self {
        Assert::lessThanEq($proposedStartDate, $proposedEndDate);
        Assert::greaterThanEq($proposedEndDate, $proposedStartDate);
        Assert::lengthBetween($timeZone->getName(), 1, 32);
        $this->proposedStartDate = $proposedStartDate;
        $this->proposedEndDate   = $proposedEndDate;
        $this->timeZone          = $timeZone->getName();
        $this->timeZoneInstance  = $timeZone;
        return $this;
    }

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue
    {
        /** @var EventVenue $venue */
        $venue = $this->getRelatedEntity($this->venue, 'Event application is not attached to a venue.');
        return $venue;
    }

    /**
     * @param EventVenue $venue
     * @return $this
     */
    public function setVenue(EventVenue $venue): self
    {
        return $this->setRelatedEntity($this->venue, $venue);
    }

    /**
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        if (!$this->timeZoneInstance) {
            $this->timeZoneInstance = new DateTimeZone($this->timeZone);
        }
        return $this->timeZoneInstance;
    }

    /**
     * @return string
     */
    public function getTimeZoneName(): string
    {
        return Timezone::getName($this->getTimeZone());
    }
}
