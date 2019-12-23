<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-07
 * Time: 14:46
 */

namespace App\Infrastructure\File;

use App\Domain\Model\File\File;
use App\Domain\Model\File\FileBinary;
use App\Domain\Model\File\FileInterface;
use DateTimeImmutable;

/**
 * Class UploadedFile
 *
 * @package App\Infrastructure\File
 */
class UploadedFile implements FileInterface
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @param File   $file
     * @param string $path
     * @param string $url
     */
    public function __construct(File $file, string $path, string $url)
    {
        $this->file = $file;
        $this->path = $path;
        $this->url  = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->file->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->file->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalName(): ?string
    {
        return $this->file->getOriginalName();
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): int
    {
        return $this->file->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType(): string
    {
        return $this->file->getMimeType();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin(): string
    {
        return $this->file->getOrigin();
    }

    /**
     * {@inheritdoc}
     */
    public function getBinary(): FileBinary
    {
        return $this->file->getBinary();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->file->getCreatedAt();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
