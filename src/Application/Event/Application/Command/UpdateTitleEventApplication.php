<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:30
 */

namespace App\Application\Event\Application\Command;

use App\Application\Common\Command\IdAware;
use App\Application\Common\ContactPerson;
use App\Domain\Model\Event\Application\TitleEventApplication;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Class UpdateTitleEventApplication
 *
 * @package App\Application\Event\Application\Command
 */
class UpdateTitleEventApplication
{
    use IdAware, TitleEventApplicationProperties;

    /**
     * @param TitleEventApplication $application
     * @return self
     */
    public static function update(TitleEventApplication $application): self
    {
        $contact = $application->getContact();
        return new self(
            $application->getId(),
            $application->getApplicantClub(),
            ContactPerson::fromContactPersonEntity($contact),
            $application->getProposedStartDate(),
            $application->getProposedEndDate(),
            $application->getVenue()->getId(),
            $application->getTimeZone()
        );
    }

    /**
     * @param string             $id
     * @param string             $applicantClub
     * @param ContactPerson      $contact
     * @param DateTimeImmutable $proposedStartDate
     * @param DateTimeImmutable $proposedEndDate
     * @param string             $venue
     * @param DateTimeZone      $timeZone
     */
    private function __construct(
        string $id,
        string $applicantClub,
        ContactPerson $contact,
        DateTimeImmutable $proposedStartDate,
        DateTimeImmutable $proposedEndDate,
        string $venue,
        DateTimeZone $timeZone
    ) {
        $this->id                = $id;
        $this->applicantClub     = $applicantClub;
        $this->contact           = $contact;
        $this->proposedStartDate = $proposedStartDate;
        $this->proposedEndDate   = $proposedEndDate;
        $this->venue             = $venue;
        $this->timeZone          = $timeZone;
    }
}
