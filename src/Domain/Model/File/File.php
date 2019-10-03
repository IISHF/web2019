<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:36
 */

namespace App\Domain\Model\File;

use App\Domain\Common\Urlizer;
use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class File
 *
 * @package App\Domain\Model\File
 *
 * @ORM\Entity(repositoryClass="FileRepository")
 * @ORM\Table(
 *      name="files",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_file_name", columns={"name"})
 *      },
 *      indexes={
 *          @ORM\Index(name="idx_file_origin", columns={"origin"})
 *      }
 * )
 */
class File implements FileInterface
{
    use HasId, CreateTracking, AssociationOne;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="original_name", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $originalName;

    /**
     * @ORM\Column(name="size", type="integer", options={"unsigned": true})
     *
     * @var int
     */
    private $size;

    /**
     * @ORM\Column(name="mime_type", type="string", length=64)
     *
     * @var string
     */
    private $mimeType;

    /**
     * @ORM\Column(name="origin", type="string", length=128)
     *
     * @var string
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity="FileBinary")
     * @ORM\JoinColumn(name="binary_hash", referencedColumnName="hash", nullable=false)
     *
     * @var FileBinary
     */
    private $binary;

    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $originalName
     * @param int         $size
     * @param string      $mimeType
     * @param string      $origin
     * @param FileBinary  $binary
     */
    public function __construct(
        string $id,
        string $name,
        ?string $originalName,
        int $size,
        string $mimeType,
        ?string $origin,
        FileBinary $binary
    ) {
        $this->setId($id)
             ->setName($name)
             ->setOriginalName($originalName)
             ->setSize($size)
             ->setMimeType($mimeType)
             ->setOrigin($origin)
             ->setBinary($binary)
             ->initCreateTracking();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    private function setName(string $name): self
    {
        Assert::lengthBetween($name, 1, 64);
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    /**
     * @param string|null $originalName
     * @return $this
     */
    private function setOriginalName(?string $originalName): self
    {
        Assert::nullOrLengthBetween($originalName, 0, 128);
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->originalName ?? $this->name;
    }

    /**
     * @return string
     */
    public function getSafeClientName(): string
    {
        $pathInfo = pathinfo($this->getClientName());
        return Urlizer::urlize($pathInfo['filename']) . '.' . $pathInfo['extension'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    private function setSize(int $size): self
    {
        Assert::greaterThanEq($size, 0);
        $this->size = $size;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    private function setMimeType(string $mimeType): self
    {
        Assert::lengthBetween($mimeType, 1, 64);
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return strpos($this->mimeType, 'image/') === 0;
    }

    /**
     * @return bool
     */
    public function isPdf(): bool
    {
        return $this->mimeType === 'application/pdf' || $this->mimeType === 'application/x-pdf';
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return $this
     */
    private function setOrigin(string $origin): self
    {
        Assert::nullOrLengthBetween($origin, 0, 128);
        $this->origin = $origin;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinary(): FileBinary
    {
        /** @var FileBinary $binary */
        $binary = $this->getRelatedEntity($this->binary, 'File it not attached to a binary.');
        return $binary;
    }

    /**
     * @param FileBinary $binary
     * @return $this
     */
    private function setBinary(FileBinary $binary): self
    {
        return $this->setRelatedEntity($this->binary, $binary);
    }

    /**
     * @return string
     */
    public function getEtag(): string
    {
        return $this->binary->getHash();
    }

    /**
     * @param string|null $filename
     * @return string
     */
    public function writeTo(?string $filename): string
    {
        if ($filename === null && ($filename = tempnam(sys_get_temp_dir(), 'file_')) === false) {
            throw new \RuntimeException('Cannot create a temporary filename.');
        }
        if (file_put_contents($filename, $this->binary->getData(), LOCK_EX) === false) {
            throw new \RuntimeException('Cannot write to file ' . $filename . '.');
        }
        return $filename;
    }
}
