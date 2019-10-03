<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 15:46
 */

namespace App\Domain\Model\Document;

use App\Domain\Model\Common\AssociationMany;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Document
 *
 * @package App\Domain\Model\Document
 *
 * @ORM\Entity(repositoryClass="DocumentRepository")
 * @ORM\Table(
 *      name="documents",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_document_title", columns={"title"}),
 *          @ORM\UniqueConstraint(name="uniq_document_slug", columns={"slug"})
 *      }
 * )
 */
class Document
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
     * @ORM\Column(name="tags", type="json")
     *
     * @var string[]
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="DocumentVersion", mappedBy="document")
     *
     * @var DocumentVersion[]|Collection
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
        $this->setId($id)
             ->setTitle($title)
             ->setSlug($slug)
             ->setTags($tags)
             ->initCreateTracking()
             ->initUpdateTracking();
        $this->versions = new ArrayCollection();
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
        $collator = \Collator::create('en-US');
        $collator->sort($tags);
        $this->tags = array_unique($tags);
        return $this;
    }

    /**
     * @return DocumentVersion[]
     */
    public function getVersions(): array
    {
        return $this->getRelatedEntities($this->versions);
    }

    /**
     * @param \DateTimeImmutable|null $date
     * @return DocumentVersion[]
     */
    public function findValidVersions(?\DateTimeImmutable $date = null): array
    {
        $date  = $date ?? new \DateTimeImmutable('now');
        $valid = [];
        foreach ($this->versions as $version) {
            if ($version->isValid($date)) {
                $valid[] = $version;
            }
        }
        return $valid;
    }

    /**
     * @param \DateTimeImmutable|null $date
     * @return DocumentVersion|null
     */
    public function findFirstValidVersion(?\DateTimeImmutable $date = null): ?DocumentVersion
    {
        $date = $date ?? new \DateTimeImmutable('now');
        foreach ($this->versions as $version) {
            if ($version->isValid($date)) {
                return $version;
            }
        }
        return null;
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
     * @param DocumentVersion $version
     * @return $this
     * @internal
     */
    public function addVersion(DocumentVersion $version): self
    {
        return $this->addRelatedEntity(
            $this->versions,
            $version,
            static function (self $me, DocumentVersion $other) {
                $other->setDocument($me);
                $me->initUpdateTracking();
            }
        );
    }

    /**
     * @param DocumentVersion $version
     * @return $this
     * @internal
     */
    public function removeVersion(DocumentVersion $version): self
    {
        return $this->removeRelatedEntity(
            $this->versions,
            $version,
            static function (self $me, DocumentVersion $other) {
                $other->setDocument(null);
                $me->initUpdateTracking();
            }
        );
    }
}
