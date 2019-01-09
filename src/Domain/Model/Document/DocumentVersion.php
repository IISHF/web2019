<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 15:47
 */

namespace App\Domain\Model\Document;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\File\File;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class DocumentVersion
 *
 * @package App\Domain\Model\Document
 *
 * @ORM\Entity()
 * @ORM\Table(
 *      name="document_versions",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_document_version", columns={"document_id", "version"}),
 *          @ORM\UniqueConstraint(name="uniq_document_slug", columns={"document_id", "slug"})
 *      }
 * )
 */
class DocumentVersion
{
    use CreateTracking, UpdateTracking;

    public const FILE_ORIGIN = 'com.iishf.document';

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Document", inversedBy="versions")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Document|null
     */
    private $document;

    /**
     * @ORM\OneToOne(targetEntity="\App\Domain\Model\File\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(name="version", type="string", length=128)
     *
     * @var string
     */
    private $version;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="valid_from", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $validFrom;

    /**
     * @ORM\Column(name="valid_until", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $validUntil;

    /**
     * @param string                  $id
     * @param Document                $document
     * @param File                    $file
     * @param string                  $version
     * @param string                  $slug
     * @param \DateTimeImmutable|null $validFrom
     * @param \DateTimeImmutable|null $validUntil
     */
    public function __construct(
        string $id,
        Document $document,
        File $file,
        string $version,
        string $slug,
        ?\DateTimeImmutable $validFrom,
        ?\DateTimeImmutable $validUntil
    ) {
        Assert::uuid($id);

        $this->id   = $id;
        $this->file = $file;
        $this->setDocument($document)
             ->setVersion($version)
             ->setSlug($slug)
             ->setValidFrom($validFrom)
             ->setValidUntil($validUntil)
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
     * @return Document
     */
    public function getDocument(): Document
    {
        if (!$this->document) {
            throw new \BadMethodCallException('Document version is not attached to a document.');
        }
        return $this->document;
    }

    /**
     * @internal
     *
     * @param Document|null $document
     * @return $this
     */
    public function setDocument(?Document $document): self
    {
        if ($document === $this->document) {
            return $this;
        }

        if ($this->document) {
            $previousDocument = $this->document;
            $this->document   = null;
            $previousDocument->removeVersion($this);
        }
        if ($document) {
            $this->document = $document;
            $document->addVersion($this);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function removeFromDocument(): self
    {
        return $this->setDocument(null);
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getFile()->getName();
    }

    /**
     * @return string
     */
    public function getFileClientName(): string
    {
        return $this->getFile()->getClientName();
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->getFile()->getMimeType();
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->getFile()->getSize();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getDocument()->getTitle() . ' ' . $this->getVersion();
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): self
    {
        Assert::lengthBetween($version, 1, 128);
        $this->version = $version;
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
     * @return \DateTimeImmutable|null
     */
    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTimeImmutable|null $validFrom
     * @return $this
     */
    public function setValidFrom(?\DateTimeImmutable $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }

    /**
     * @param \DateTimeImmutable|null $validUntil
     * @return $this
     */
    public function setValidUntil(?\DateTimeImmutable $validUntil): self
    {
        $this->validUntil = $validUntil;
        return $this;
    }
}
