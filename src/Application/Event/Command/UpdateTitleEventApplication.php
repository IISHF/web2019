<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:30
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Common\ContactPerson;
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\TitleEventApplication;

/**
 * Class UpdateTitleEventApplication
 *
 * @package App\Application\Event\Command
 */
class UpdateTitleEventApplication
{
    use UuidAware, TitleEventApplicationProperties;

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
            $application->getVenue()
        );
    }

    /**
     * @param string             $id
     * @param string             $applicantClub
     * @param ContactPerson      $contact
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     * @param EventVenue         $venue
     */
    private function __construct(
        string $id,
        string $applicantClub,
        ContactPerson $contact,
        \DateTimeImmutable $proposedStartDate,
        \DateTimeImmutable $proposedEndDate,
        EventVenue $venue
    ) {
        $this->id                = $id;
        $this->applicantClub     = $applicantClub;
        $this->contact           = $contact;
        $this->proposedStartDate = $proposedStartDate;
        $this->proposedEndDate   = $proposedEndDate;
        $this->venue             = $venue;
    }
}
