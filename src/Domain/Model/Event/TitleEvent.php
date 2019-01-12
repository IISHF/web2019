<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:13
 */

namespace App\Domain\Model\Event;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class TitleEvent
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 */
abstract class TitleEvent extends Event
{
    public const STATE_PLANNED = 'planned';

    /**
     * @ORM\Column(name="current_state", type="string", length=16)
     *
     * @var string
     */
    private $currentState;

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
    protected function __construct(
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

        $this->currentState = self::STATE_PLANNED;
        $this->setPlannedLength($plannedLength)
             ->setDescription($description);
    }

    /**
     * @return string
     */
    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    /**
     * @param string $currentState
     * @return $this
     */
    public function setCurrentState(string $currentState): self
    {
        Assert::lengthBetween($currentState, 1, 16);
        $this->currentState = $currentState;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPlanned(): bool
    {
        return $this->currentState === self::STATE_PLANNED;
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
     * @param EventContact       $contact
     * @param \DateTimeImmutable $proposedStartDate
     * @param \DateTimeImmutable $proposedEndDate
     * @return TitleEventApplication
     */
    public function applyForEvent(
        string $id,
        EventContact $contact,
        \DateTimeImmutable $proposedStartDate,
        \DateTimeImmutable $proposedEndDate
    ): TitleEventApplication {
        return new TitleEventApplication(
            $id,
            $this,
            $contact,
            $proposedStartDate,
            $proposedEndDate
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
        $this->setOrganizer(
            new EventOrganizer(
                $id,
                $contact->getClub(),
                $contact->getName(),
                $contact->getEmail(),
                $contact->getPhoneNumber()
            )
        );
        $this->setDate($application->getProposedStartDate(), $application->getProposedEndDate());
        return $this;
    }
}
