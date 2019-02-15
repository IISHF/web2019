<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:57
 */

namespace App\Application\Committee\Command;

use App\Application\Committee\Validator\MemberType as ValidMemberType;
use App\Application\Committee\Validator\TermType as ValidTermType;
use App\Domain\Model\Committee\MemberType;
use App\Domain\Model\Committee\TermType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait CommitteeMemberProperties
 *
 * @package App\Application\Committee\Command
 */
trait CommitteeMemberProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $firstName = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $lastName = '';

    /**
     * @Assert\Type("string")
     * @Assert\Country()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $country = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    private $title;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @ValidTermType()
     *
     * @var int
     */
    private $termType = TermType::ELECTED;

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=2000, max=9999)
     *
     * @var int|null
     */
    private $termSince;

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=1, max=99)
     *
     * @var int|null
     */
    private $termDuration;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @ValidMemberType()
     *
     * @var int
     */
    private $memberType = MemberType::MEMBER;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getTermType(): int
    {
        return $this->termType;
    }

    /**
     * @param int $termType
     * @return $this
     */
    public function setTermType(int $termType): self
    {
        $this->termType = $termType;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTermSince(): ?int
    {
        return $this->termSince;
    }

    /**
     * @param int|null $termSince
     * @return $this
     */
    public function setTermSince(?int $termSince): self
    {
        $this->termSince = $termSince;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTermDuration(): ?int
    {
        return $this->termDuration;
    }

    /**
     * @param int|null $termDuration
     * @return $this
     */
    public function setTermDuration(?int $termDuration): self
    {
        $this->termDuration = $termDuration;
        return $this;
    }

    /**
     * @return int
     */
    public function getMemberType(): int
    {
        return $this->memberType;
    }

    /**
     * @param int $memberType
     * @return $this
     */
    public function setMemberType(int $memberType): self
    {
        $this->memberType = $memberType;
        return $this;
    }
}
