<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:19
 */

namespace App\Domain\Model\Committee;

use App\Domain\Common\Country;
use App\Domain\Entity\Team\Team;
use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class CommitteeMember
 *
 * @package App\Domain\Model\Committee
 *
 * @ORM\Entity()
 * @ORM\Table(name="committee_members")
 */
class CommitteeMember
{
    use HasId, CreateTracking, UpdateTracking, AssociationOne;

    /**
     * @ORM\ManyToOne(targetEntity="Committee", inversedBy="members")
     * @ORM\JoinColumn(name="committee_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Committee|null
     */
    private $committee;

    /**
     * @ORM\Column(name="first_name", type="string", length=128)
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=128)
     *
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(name="country", type="string", length=2)
     *
     * @var string
     */
    private $country;

    /**
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $title;

    /**
     * @ORM\Column(name="term_type", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $termType;

    /**
     * @ORM\Column(name="term_since", type="smallint", options={"unsigned": true}, nullable=true)
     *
     * @var int|null
     */
    private $termSince;

    /**
     * @ORM\Column(name="term_duration", type="smallint", options={"unsigned": true}, nullable=true)
     *
     * @var int|null
     */
    private $termDuration;

    /**
     * @ORM\Column(name="member_type", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $memberType;

    /**
     * @param string      $id
     * @param Committee   $committee
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $country
     * @param string|null $title
     * @param int         $termType
     * @param int|null    $termSince
     * @param int|null    $termDuration
     * @param int         $memberType
     */
    public function __construct(
        string $id,
        Committee $committee,
        string $firstName,
        string $lastName,
        string $country,
        ?string $title,
        int $termType,
        ?int $termSince,
        ?int $termDuration,
        int $memberType
    ) {
        $this->setId($id)
             ->setCommittee($committee)
             ->setFirstName($firstName)
             ->setLastName($lastName)
             ->setCountry($country)
             ->setTitle($title)
             ->setTerm($termType, $termSince, $termDuration)
             ->setMemberType($memberType)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return Committee
     */
    public function getCommittee(): Committee
    {
        /** @var Committee $committee */
        $committee = $this->getRelatedEntity($this->committee, 'Committee member is not attached to a committee.');
        return $committee;
    }

    /**
     * @param Committee|null $committee
     * @return $this
     * @internal
     */
    public function setCommittee(?Committee $committee): self
    {
        return $this->setRelatedEntity(
            $this->committee,
            $committee,
            static function (Committee $other, self $me) {
                $other->addMember($me);
            },
            static function (Committee $other, self $me) {
                $other->removeMember($me);
            }
        );
    }

    /**
     * @return $this
     */
    public function removeFromCommittee(): self
    {
        return $this->setCommittee(null);
    }

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
        Assert::lengthBetween($firstName, 1, 128);
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
        Assert::lengthBetween($lastName, 1, 128);
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param bool $lastNameFirst
     * @return string
     */
    public function getName(bool $lastNameFirst = false): string
    {
        if ($lastNameFirst) {
            return $this->lastName . ', ' . $this->firstName;
        }
        return $this->firstName . ' ' . $this->lastName;
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
        $country = mb_strtoupper($country, 'UTF-8');
        Assert::length($country, 2);
        Country::assertValidCountry($country);
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return Country::getCountryNameByCode($this->country);
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
        Assert::nullOrLengthBetween($title, 1, 128);
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
     * @return int
     */
    public function getTermSince(): int
    {
        return $this->termSince;
    }

    /**
     * @return int
     */
    public function getTermDuration(): int
    {
        return $this->termDuration;
    }

    /**
     * @return string
     */
    public function getTermTypeDescription(): string
    {
        $type = TermType::getTermTypeName($this->termType, 'unknown');
        if ($this->termSince !== null) {
            $type .= ' in ' . $this->termSince;
        }
        if ($this->termDuration !== null) {
            $type .= ' for ' . $this->termDuration . ' years';
        }

        return $type;
    }

    /**
     * @param int      $termType
     * @param int|null $termSince
     * @param int|null $termDuration
     * @return $this
     */
    public function setTerm(int $termType, ?int $termSince, ?int $termDuration): self
    {
        TermType::assertValidTermType($termType);
        Assert::nullOrRange($termSince, 2000, 9999);
        Assert::nullOrRange($termDuration, 1, 99);
        $this->termType     = $termType;
        $this->termSince    = $termSince;
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
     * @return bool
     */
    public function isChairman(): bool
    {
        return $this->memberType === MemberType::CHAIRMAN;
    }

    /**
     * @return bool
     */
    public function isViceChairman(): bool
    {
        return $this->memberType === MemberType::VICE_CHAIRMAN;
    }

    /**
     * @return bool
     */
    public function isMember(): bool
    {
        return !$this->isChairman() && !$this->isViceChairman();
    }

    /**
     * @param int $memberType
     * @return $this
     */
    public function setMemberType(int $memberType): self
    {
        MemberType::assertValidMemberType($memberType);
        $this->memberType = $memberType;
        return $this;
    }
}
