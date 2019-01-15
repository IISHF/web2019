<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:13
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\ContactPerson;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class TitleEvent
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\MappedSuperclass()
 */
abstract class TitleEvent extends Event
{
    /**
     * @ORM\Column(name="planned_length", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $plannedLength;

    /**
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     *
     * @var string|null
     */
    private $description;

    /**
     * @param string      $id
     * @param string      $name
     * @param string      $slug
     * @param int         $season
     * @param string      $ageGroup
     * @param int         $plannedLength
     * @param string|null $description
     * @param array       $tags
     */
    public function __construct(
        string $id,
        string $name,
        string $slug,
        int $season,
        string $ageGroup,
        int $plannedLength,
        ?string $description,
        array $tags
    ) {
        parent::__construct($id, $name, $slug, $season, $ageGroup, $tags);

        $this->setPlannedLength($plannedLength)
             ->setDescription($description);
    }

    /**
     * @return int
     */
    public function getPlannedLength(): int
    {
        return $this->plannedLength;
    }

    /**
     * @param int $plannedLength
     * @return $this
     */
    public function setPlannedLength(int $plannedLength): self
    {
        Assert::range($plannedLength, 1, 31);
        $this->plannedLength = $plannedLength;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        Assert::nullOrLengthBetween($description, 1, 65535);
        $this->description = $description;
        return $this;
    }

    /**
     * @param string             $id
     * @param string             $applicantClub
     * @param ContactPerson      $contact
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     * @param EventVenue         $venue
     * @return TitleEventApplication
     */
    public function applyForEvent(
        string $id,
        string $applicantClub,
        ContactPerson $contact,
        \DateTimeImmutable $proposedStartDate,
        \DateTimeImmutable $proposedEndDate,
        EventVenue $venue
    ): TitleEventApplication {
        return new TitleEventApplication(
            $id,
            $this,
            $applicantClub,
            $contact,
            $proposedStartDate,
            $proposedEndDate,
            $venue
        );
    }

    /**
     * @param string                $id
     * @param TitleEventApplication $application
     * @return $this
     */
    public function awardEventApplication(string $id, TitleEventApplication $application): self
    {
        if ($application->getTitleEvent() !== $this) {
            throw new \InvalidArgumentException('The title event application is not registered with this event.');
        }
        $contact = $application->getContact();
        $this->setHost(
            new EventHost(
                $id,
                $application->getApplicantClub(),
                $contact->getName(),
                $contact->getEmail(),
                $contact->getPhoneNumber()
            )
        )
             ->setDate($application->getProposedStartDate(), $application->getProposedEndDate())
             ->setVenue($application->getVenue());

        return $this;
    }
}
