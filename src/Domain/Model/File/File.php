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
 *          @ORM\Index(name="idx_file_origin", columns={"origin"})
 *      }
 * )
 */
class File implements FileInterface
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
     * @ORM\JoinColumn(name="binary_hash", referencedColumnName="hash", nullable=false, onDelete="CASCADE")
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
        Assert::uuid($id);
        Assert::lengthBetween($name, 1, 64);
        Assert::nullOrLengthBetween($originalName, 0, 128);
        Assert::greaterThanEq($size, 0);
        Assert::lengthBetween($mimeType, 1, 64);
        Assert::nullOrLengthBetween($origin, 0, 128);

        $this->id           = $id;
        $this->name         = $name;
        $this->originalName = $originalName;
        $this->size         = $size;
        $this->mimeType     = $mimeType;
        $this->origin       = $origin;
        $this->binary       = $binary;
        $this->initCreateTracking();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
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
     * {@inheritdoc}
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
