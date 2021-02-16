<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:19
 */

namespace App\Domain\Model\Committee;

use App\Domain\Model\Common\AssociationMany;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Committee
 *
 * @package App\Domain\Model\Committee
 *
 * @ORM\Entity(repositoryClass="CommitteeRepository")
 * @ORM\Table(
 *      name="committees",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_committee_title", columns={"title"}),
 *          @ORM\UniqueConstraint(name="uniq_committee_slug", columns={"slug"})
 *      }
 * )
 */
class Committee
{
    use HasId, CreateTracking, UpdateTracking, AssociationMany;

    /**
     * @ORM\Column(name="title", type="string", length=128)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     *
     * @var string|null
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="CommitteeMember", mappedBy="committee")
     *
     * @var CommitteeMember[]|Collection
     */
    private $members;

    /**
     * @param string      $id
     * @param string      $title
     * @param string      $slug
     * @param string|null $description
     */
    public function __construct(string $id, string $title, string $slug, ?string $description)
    {
        $this->setId($id)
             ->setTitle($title)
             ->setSlug($slug)
             ->setDescription($description)
             ->initCreateTracking()
             ->initUpdateTracking();
        $this->members = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        Assert::lengthBetween($title, 1, 128);
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        Assert::regex($slug, '/^[0-9a-z-]+$/');
        Assert::lengthBetween($slug, 1, 128);
        $this->slug = $slug;
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
     * @return CommitteeMember[]
     */
    public function getMembers(): array
    {
        return $this->getRelatedEntities($this->members);
    }

    /**
     * @param string      $id
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $country
     * @param int         $memberType
     * @param string|null $title
     * @param int         $termType
     * @param int|null    $termSince
     * @param int|null    $termDuration
     * @return CommitteeMember
     */
    public function createMember(
        string $id,
        string $firstName,
        string $lastName,
        string $country,
        int $memberType,
        ?string $title,
        int $termType,
        ?int $termSince,
        ?int $termDuration
    ): CommitteeMember {
        $member = new CommitteeMember(
            $id,
            $this,
            $firstName,
            $lastName,
            $country,
            $memberType,
            $title,
            $termType,
            $termSince,
            $termDuration,
        );
        $this->addMember($member);
        return $member;
    }

    /**
     * @param CommitteeMember $member
     * @return $this
     * @internal
     */
    public function addMember(CommitteeMember $member): self
    {
        return $this->addRelatedEntity(
            $this->members,
            $member,
            static function (self $me, CommitteeMember $other) {
                $other->setCommittee($me);
                $me->initUpdateTracking();
            }
        );
    }

    /**
     * @param CommitteeMember $member
     * @return $this
     * @internal
     */
    public function removeMember(CommitteeMember $member): self
    {
        return $this->removeRelatedEntity(
            $this->members,
            $member,
            static function (self $me, CommitteeMember $other) {
                $other->setCommittee(null);
                $me->initUpdateTracking();
            }
        );
    }
}
