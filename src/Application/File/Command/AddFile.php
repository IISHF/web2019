<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:39
 */

namespace App\Application\File\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddFile
 *
 * @package App\Application\File\Command
 */
class AddFile
{
    use UuidAware;

    /**
     * @Assert\File(
     *      maxSize="16M",
     *      mimeTypes={
     *          "image/*",
     *          "text/*",
     *          "application/pdf",
     *          "application/x-pdf",
     *          "application/zip",
     *          "application/vnd.ms-excel",
     *          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *          "application/msword",
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *          "application/vnd.ms-powerpoint",
     *          "application/vnd.openxmlformats-officedocument.presentationml.presentation"
     *      }
     * )
     *
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    private $origin;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    private $originalName;

    /**
     * @param \SplFileInfo $file
     * @param string       $origin
     * @param string|null  $originalName
     * @return self
     */
    public static function add(\SplFileInfo $file, string $origin, ?string $originalName = null): self
    {
        return new self(self::createUuid(), $file, $origin, $originalName);
    }

    /**
     * @param string       $id
     * @param \SplFileInfo $file
     * @param string       $origin
     * @param string|null  $originalName
     */
    private function __construct(
        string $id,
        \SplFileInfo $file,
        string $origin,
        ?string $originalName = null
    ) {
        $this->id           = $id;
        $this->file         = $file;
        $this->origin       = $origin;
        $this->originalName = $originalName;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }
}
