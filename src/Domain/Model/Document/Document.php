<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 15:46
 */

namespace App\Domain\Model\Document;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Document
 *
 * @package App\Domain\Model\Document
 *
 * @ORM\Entity(repositoryClass="DocumentRepository")
 * @ORM\Table(name="documents")
 */
class Document
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
     * @ORM\Column(name="title", type="string", length=128, unique=true)
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
     * @ORM\Column(name="tags", type="json")
     *
     * @var string[]
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="document", cascade={"PERSIST", "REMOVE"})
     *
     * @var DocumentVersion[]|ArrayCollection
     */
    private $versions;

    /**
     * @param string $id
     * @param string $title
     * @param string $slug
     * @param array  $tags
     */
    public function __construct(string $id, string $title, string $slug, array $tags)
    {
        Assert::uuid($id);

        $this->id       = $id;
        $this->versions = new ArrayCollection();
        $this->setTitle($title)
             ->setSlug($slug)
             ->setTags($tags)
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
        $this->tags = array_unique($tags);
        return $this;
    }

    /**
     * @return DocumentVersion[]
     */
    public function getVersions(): array
    {
        return $this->versions->toArray();
    }

    /**
     * @param string                  $id
     * @param File                    $file
     * @param string                  $version
     * @param string                  $slug
     * @param \DateTimeImmutable|null $validFrom
     * @param \DateTimeImmutable|null $validUntil
     * @return DocumentVersion
     */
    public function createVersion(
        string $id,
        File $file,
        string $version,
        string $slug,
        ?\DateTimeImmutable $validFrom = null,
        ?\DateTimeImmutable $validUntil = null
    ): DocumentVersion {
        $documentVersion = new DocumentVersion($id, $this, $file, $version, $slug, $validFrom, $validUntil);
        $this->addVersion($documentVersion);
        return $documentVersion;
    }

    /**
     * @internal
     *
     * @param DocumentVersion $version
     * @return $this
     */
    public function addVersion(DocumentVersion $version): self
    {
        if ($this->versions->contains($version)) {
            return $this;
        }
        $this->versions->add($version);
        $version->setDocument($this);
        $this->initUpdateTracking();
        return $this;
    }

    /**
     * @param DocumentVersion $version
     * @return $this
     */
    public function removeVersion(DocumentVersion $version): self
    {
        if (!$this->versions->contains($version)) {
            return $this;
        }
        $this->versions->removeElement($version);
        $version->setDocument(null);
        $this->initUpdateTracking();
        return $this;
    }
}
