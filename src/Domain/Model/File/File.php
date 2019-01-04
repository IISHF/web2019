<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:36
 */

namespace App\Domain\Model\File;

use App\Domain\Common\Urlizer;
use App\Domain\Model\Common\CreateTracking;
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
 *      indexes={
 *          @ORM\Index(name="idx_file_reference", columns={"reference"})
 *      }
 * )
 */
class File
{
    use CreateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=64, unique=true)
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
     * @var string
     */
    private $size;

    /**
     * @ORM\Column(name="mime_type", type="string", length=64)
     *
     * @var string
     */
    private $mimeType;

    /**
     * @ORM\Column(name="reference", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="FileBinary", cascade={"PERSIST"})
     * @ORM\JoinColumn(name="binary_hash", referencedColumnName="hash", onDelete="CASCADE")
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
     * @param string|null $reference
     * @param FileBinary  $binary
     * @return File
     */
    public static function create(
        string $id,
        string $name,
        ?string $originalName,
        int $size,
        string $mimeType,
        ?string $reference,
        FileBinary $binary
    ): self {
        return new self($id, $name, $originalName, $size, $mimeType, $reference, $binary);
    }

    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $originalName
     * @param int         $size
     * @param string      $mimeType
     * @param string|null $reference
     * @param FileBinary  $binary
     */
    private function __construct(
        string $id,
        string $name,
        ?string $originalName,
        int $size,
        string $mimeType,
        ?string $reference,
        FileBinary $binary
    ) {
        Assert::uuid($id);
        Assert::lengthBetween($name, 1, 64);
        Assert::nullOrLengthBetween($originalName, 0, 128);
        Assert::greaterThanEq($size, 0);
        Assert::lengthBetween($mimeType, 1, 64);
        Assert::nullOrLengthBetween($reference, 0, 128);

        $this->id           = $id;
        $this->name         = $name;
        $this->originalName = $originalName;
        $this->size         = $size;
        $this->mimeType     = $mimeType;
        $this->reference    = $reference;
        $this->binary       = $binary;
        $this->initCreateTracking();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->getOriginalName() ?? $this->getName();
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
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return FileBinary
     */
    public function getBinary(): FileBinary
    {
        return $this->binary;
    }

    /**
     * @return string
     */
    public function getEtag(): string
    {
        return $this->getBinary()->getHash();
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
        if (file_put_contents($filename, $this->getBinary()->getData(), LOCK_EX) === false) {
            throw new \RuntimeException('Cannot write to file ' . $filename . '.');
        }
        return $filename;
    }
}
