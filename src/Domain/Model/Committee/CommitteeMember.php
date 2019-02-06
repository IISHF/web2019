<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:19
 */

namespace App\Domain\Model\Committee;

use App\Domain\Common\Country;
use App\Domain\Model\Common\CreateTracking;
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
    use CreateTracking, UpdateTracking;

    public const TERM_TYPE_ELECTED         = 1;
    public const TERM_TYPE_NOMINATED_IISHF = 2;
    public const TERM_TYPE_NOMINATED_NGB   = 3;

    /**
     * @var array
     */
    private static $availableTermTypes = [
        self::TERM_TYPE_ELECTED         => 'Elected by AGM',
        self::TERM_TYPE_NOMINATED_IISHF => 'Nominated by IISHF',
        self::TERM_TYPE_NOMINATED_NGB   => 'Nominated by NGB',
    ];

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

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
     * @return array
     */
    public static function getTermTypes(): array
    {
        return self::$availableTermTypes;
    }

    /**
     * @param int         $termType
     * @param string|null $default
     * @return string|null
     */
    public static function getTermTypeName(int $termType, ?string $default = null): ?string
    {
        return self::$availableTermTypes[$termType] ?? $default;
    }

    /**
     * @param int $termType
     * @return bool
     */
    public static function isValidTermType(int $termType): bool
    {
        return isset(self::$availableTermTypes[$termType]);
    }

    /**
     * @param int $termType
     */
    public static function assertValidTermType(int $termType): void
    {
        Assert::oneOf($termType, array_keys(self::$availableTermTypes));
    }

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
        ?int $termDuration
    ) {
        Assert::uuid($id);

        $this->id = $id;
        $this->setCommittee($committee)
             ->setFirstName($firstName)
             ->setLastName($lastName)
             ->setCountry($country)
             ->setTitle($title)
             ->setTerm($termType, $termSince, $termDuration)
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
     * @return Committee
     */
    public function getCommittee(): Committee
    {
        if (!$this->committee) {
            throw new \BadMethodCallException('Committee member is not attached to a committee.');
        }
        return $this->committee;
    }

    /**
     * @internal
     *
     * @param Committee|null $committee
     * @return $this
     */
    public function setCommittee(?Committee $committee): self
    {
        if ($committee === $this->committee) {
            return $this;
        }

        if ($this->committee) {
            $previousCommittee = $this->committee;
            $this->committee   = null;
            $previousCommittee->removeMember($this);
        }
        if ($committee) {
            $this->committee = $committee;
            $committee->addMember($this);
        }
        return $this;
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
        $type = self::getTermTypeName($this->termType, 'unknown');
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
        self::assertValidTermType($termType);
        Assert::nullOrRange($termSince, 2000, 9999);
        Assert::nullOrRange($termDuration, 1, 99);
        $this->termType     = $termType;
        $this->termSince    = $termSince;
        $this->termDuration = $termDuration;
        return $this;
    }
}
