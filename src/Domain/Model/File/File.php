<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:36
 */

namespace App\Domain\Model\File;

use App\Domain\Model\Common\ChangeTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class File
 *
 * @package App\Domain\Model\File
 *
 * @ORM\Entity(repositoryClass="FileRepository")
 * @ORM\Table(name="files")
 */
class File
{
    use ChangeTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=128)
     *
     * @var string
     */
    private $name;

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
     * @ORM\ManyToOne(targetEntity="FileBinary", cascade={"PERSIST"})
     * @ORM\JoinColumn(name="binary_hash", referencedColumnName="hash", onDelete="CASCADE")
     *
     * @var FileBinary
     */
    private $binary;

    /**
     * @param string     $id
     * @param string     $name
     * @param int        $size
     * @param string     $mimeType
     * @param FileBinary $binary
     * @return File
     */
    public static function create(string $id, string $name, int $size, string $mimeType, FileBinary $binary): self
    {
        return new self($id, $name, $size, $mimeType, $binary);
    }

    /**
     * @param string     $id
     * @param string     $name
     * @param int        $size
     * @param string     $mimeType
     * @param FileBinary $binary
     */
    private function __construct(string $id, string $name, int $size, string $mimeType, FileBinary $binary)
    {
        Assert::uuid($id);
        Assert::greaterThanEq($size, 0);
        Assert::lengthBetween($mimeType, 1, 64);

        $this->id       = $id;
        $this->size     = $size;
        $this->mimeType = $mimeType;
        $this->binary   = $binary;
        $this->setName($name)
             ->initChangeTracking();
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
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
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
}
