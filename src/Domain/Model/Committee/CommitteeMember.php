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
     * @param string      $id
     * @param Committee   $committee
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $country
     * @param string|null $title
     */
    public function __construct(
        string $id,
        Committee $committee,
        string $firstName,
        string $lastName,
        string $country,
        ?string $title
    ) {
        Assert::uuid($id);

        $this->id = $id;
        $this->setCommittee($committee)
             ->setFirstName($firstName)
             ->setLastName($lastName)
             ->setCountry($country)
             ->setTitle($title)
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
}
