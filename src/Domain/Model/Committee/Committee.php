<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:19
 */

namespace App\Domain\Model\Committee;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\Common\Collections\ArrayCollection;
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
    use CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

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
     * @var CommitteeMember[]|ArrayCollection
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
        Assert::uuid($id);

        $this->id      = $id;
        $this->members = new ArrayCollection();
        $this->setTitle($title)
             ->setSlug($slug)
             ->setDescription($description)
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
        return $this->members->toArray();
    }

    /**
     * @param string      $id
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $country
     * @param string|null $title
     * @return CommitteeMember
     */
    public function createMember(
        string $id,
        string $firstName,
        string $lastName,
        string $country,
        ?string $title
    ): CommitteeMember {
        $member = new CommitteeMember($id, $this, $firstName, $lastName, $country, $title);
        $this->addMember($member);
        return $member;
    }

    /**
     * @internal
     *
     * @param CommitteeMember $member
     * @return $this
     */
    public function addMember(CommitteeMember $member): self
    {
        if ($this->members->contains($member)) {
            return $this;
        }
        $this->members->add($member);
        $member->setCommittee($this);
        $this->initUpdateTracking();
        return $this;
    }

    /**
     * @internal
     *
     * @param CommitteeMember $member
     * @return $this
     */
    public function removeMember(CommitteeMember $member): self
    {
        if (!$this->members->contains($member)) {
            return $this;
        }
        $this->members->removeElement($member);
        $member->setCommittee(null);
        $this->initUpdateTracking();
        return $this;
    }
}
