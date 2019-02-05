<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:41
 */

namespace App\Application\Event\Command;

use App\Application\Common\Validator\AgeGroup as ValidAgeGroup;
use App\Application\Event\Validator\UniqueEventName;
use App\Domain\Common\AgeGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait EventProperties
 *
 * @package App\Application\Event\Command
 */
trait EventProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotBlank()
     * @UniqueEventName()
     *
     * @var string
     */
    private $name = '';

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=2000, max=9999)
     * @Assert\NotBlank()
     *
     * @var int
     */
    private $season;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     * @ValidAgeGroup()
     *
     * @var string
     */
    private $ageGroup = AgeGroup::AGE_GROUP_MEN;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\Expression(
     *      expression="not this.isSanctioned() ? value === null : true",
     *      message="This value must be empty while the event is not sanctioned yet."
     * )
     * @Assert\Expression(
     *      expression="this.isSanctioned() ? value !== null : true",
     *      message="This value must not be empty when the event is already sanctioned."
     * )
     *
     * @var string|null
     */
    private $sanctionNumber;

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string"),
     *     @Assert\NotBlank()
     * })
     *
     * @var string[]
     */
    private $tags = [];

    /**
     * @var bool
     */
    private $sanctioned = false;

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
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeason(): int
    {
        return $this->season;
    }

    /**
     * @param int $season
     * @return $this
     */
    public function setSeason(int $season): self
    {
        $this->season = $season;
        return $this;
    }

    /**
     * @return string
     */
    public function getAgeGroup(): string
    {
        return $this->ageGroup;
    }

    /**
     * @param string $ageGroup
     * @return $this
     */
    public function setAgeGroup(string $ageGroup): self
    {
        $this->ageGroup = $ageGroup;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSanctionNumber(): ?string
    {
        return $this->sanctionNumber;
    }

    /**
     * @param string|null $sanctionNumber
     * @return $this
     */
    public function setSanctionNumber(?string $sanctionNumber): self
    {
        $this->sanctionNumber = $sanctionNumber;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSanctioned(): bool
    {
        return $this->sanctioned;
    }
}
