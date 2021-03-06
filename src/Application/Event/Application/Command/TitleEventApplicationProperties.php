<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:32
 */

namespace App\Application\Event\Application\Command;

use App\Application\Common\ContactPerson;
use App\Application\Event\Application\Validator\UniqueApplicantClubName;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait TitleEventApplicationProperties
 *
 * @package App\Application\Event\Application\Command
 */
trait TitleEventApplicationProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     * @UniqueApplicantClubName()
     *
     * @var string
     */
    private $applicantClub = '';

    /**
     * @Assert\Type("App\Application\Common\ContactPerson")
     * @Assert\NotNull()
     * @Assert\Valid()
     *
     * @var ContactPerson
     */
    private $contact;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     * @Assert\LessThanOrEqual(
     *      propertyPath="proposedEndDate",
     *      message="This value should be less than or equal to the proposed end date."
     * )
     *
     * @var DateTimeImmutable
     */
    private $proposedStartDate;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(
     *      propertyPath="proposedStartDate",
     *      message="This value should be greater than or equal to the proposed start date."
     * )
     *
     * @var DateTimeImmutable
     */
    private $proposedEndDate;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $venue;

    /**
     * @Assert\Type("DateTimeZone")
     * @Assert\NotNull()
     *
     * @var DateTimeZone
     */
    private $timeZone;

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
     * @return DateTimeImmutable
     */
    public function getProposedStartDate(): DateTimeImmutable
    {
        return $this->proposedStartDate;
    }

    /**
     * @param DateTimeImmutable $proposedStartDate
     * @return $this
     */
    public function setProposedStartDate(DateTimeImmutable $proposedStartDate): self
    {
        $this->proposedStartDate = $proposedStartDate;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getProposedEndDate(): DateTimeImmutable
    {
        return $this->proposedEndDate;
    }

    /**
     * @param DateTimeImmutable $proposedEndDate
     * @return $this
     */
    public function setProposedEndDate(DateTimeImmutable $proposedEndDate): self
    {
        $this->proposedEndDate = $proposedEndDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVenue(): ?string
    {
        return $this->venue;
    }

    /**
     * @param string $venue
     * @return $this
     */
    public function setVenue(string $venue): self
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @return DateTimeZone|null
     */
    public function getTimeZone(): ?DateTimeZone
    {
        return $this->timeZone;
    }

    /**
     * @param DateTimeZone $timeZone
     * @return $this
     */
    public function setTimeZone(DateTimeZone $timeZone): self
    {
        $this->timeZone = $timeZone;
        return $this;
    }
}
