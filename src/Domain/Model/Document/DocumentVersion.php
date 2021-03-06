<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 15:47
 */

namespace App\Domain\Model\Document;

use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\File\File;
use DateTimeImmutable;
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
    use HasId, CreateTracking, UpdateTracking, AssociationOne;

    public const FILE_ORIGIN = 'com.iishf.document';

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
     * @ORM\Column(name="valid_from", type="date_immutable", nullable=true)
     *
     * @var DateTimeImmutable|null
     */
    private $validFrom;

    /**
     * @ORM\Column(name="valid_until", type="date_immutable", nullable=true)
     *
     * @var DateTimeImmutable|null
     */
    private $validUntil;

    /**
     * @param string                 $id
     * @param Document               $document
     * @param File                   $file
     * @param string                 $version
     * @param string                 $slug
     * @param DateTimeImmutable|null $validFrom
     * @param DateTimeImmutable|null $validUntil
     */
    public function __construct(
        string $id,
        Document $document,
        File $file,
        string $version,
        string $slug,
        ?DateTimeImmutable $validFrom,
        ?DateTimeImmutable $validUntil
    ) {
        $this->setId($id)
             ->setFile($file)
             ->setDocument($document)
             ->setVersion($version)
             ->setSlug($slug)
             ->setValidity($validFrom, $validUntil)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        /** @var Document $document */
        $document = $this->getRelatedEntity($this->document, 'Document version is not attached to a document.');
        return $document;
    }

    /**
     * @param Document|null $document
     * @return $this
     * @internal
     */
    public function setDocument(?Document $document): self
    {
        return $this->setRelatedEntity(
            $this->document,
            $document,
            static function (Document $other, self $me) {
                $other->addVersion($me);
            },
            static function (Document $other, self $me) {
                $other->removeVersion($me);
            }
        );
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
        /** @var File $file */
        $file = $this->getRelatedEntity($this->file, 'Document version is not attached to a file.');
        return $file;
    }

    /**
     * @param File $file
     * @return $this
     */
    private function setFile(File $file): self
    {
        return $this->setRelatedEntity($this->file, $file);
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->file->getName();
    }

    /**
     * @return string
     */
    public function getFileClientName(): string
    {
        return $this->file->getClientName();
    }

    /**
     * @return string
     */
    public function getFileType(): string
    {
        return $this->file->getMimeType();
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->file->getSize();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->document->getTitle() . ' ' . $this->version;
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
     * @return DateTimeImmutable|null
     */
    public function getValidFrom(): ?DateTimeImmutable
    {
        return $this->validFrom;
    }

    /**
     * @param DateTimeImmutable|null $validFrom
     * @return $this
     */
    public function setValidFrom(?DateTimeImmutable $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getValidUntil(): ?DateTimeImmutable
    {
        return $this->validUntil;
    }

    /**
     * @param DateTimeImmutable|null $validFrom
     * @param DateTimeImmutable|null $validUntil
     * @return $this
     */
    public function setValidity(?DateTimeImmutable $validFrom, ?DateTimeImmutable $validUntil): self
    {
        if ($validUntil !== null) {
            Assert::nullOrLessThanEq($validFrom, $validUntil);
        }
        if ($validFrom !== null) {
            Assert::nullOrGreaterThanEq($validUntil, $validFrom);
        }
        $this->validFrom  = $validFrom;
        $this->validUntil = $validUntil;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasValidity(): bool
    {
        return !($this->validFrom === null && $this->validUntil === null);
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return bool
     */
    public function isValid(?DateTimeImmutable $date = null): bool
    {
        if (!$this->hasValidity()) {
            return true;
        }
        $date = $date ?? new DateTimeImmutable('now');
        if ($this->validFrom !== null && $date < $this->validFrom) {
            return false;
        }
        if ($this->validUntil !== null && $date >= $this->validUntil->modify('+1 day')) {
            return false;
        }
        return true;
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return bool
     */
    public function isFuture(?DateTimeImmutable $date = null): bool
    {
        $date = $date ?? new DateTimeImmutable('now');
        return $this->validFrom !== null && $date < $this->validFrom;
    }

    /**
     * @param DateTimeImmutable|null $date
     * @return bool
     */
    public function isOutdated(?DateTimeImmutable $date = null): bool
    {
        $date = $date ?? new DateTimeImmutable('now');
        return $this->validUntil !== null && $date >= $this->validUntil->modify('+1 day');
    }
}
