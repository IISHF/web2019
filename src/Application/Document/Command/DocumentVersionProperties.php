<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:54
 */

namespace App\Application\Document\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait DocumentVersionProperties
 *
 * @package App\Application\Document\Command
 */
trait DocumentVersionProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $documentId;

    /**
     * @Assert\File(
     *      maxSize="16M",
     *      mimeTypes={
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
     * @Assert\Type("SplFileInfo")
     * @Assert\NotNull()
     *
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $version = '';

    /**
     * @Assert\Type("\DateTimeImmutable")
     *
     * @var \DateTimeImmutable|null
     */
    private $validFrom;

    /**
     * @Assert\Type("\DateTimeImmutable")
     *
     * @var \DateTimeImmutable|null
     */
    private $validUntil;

    /**
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
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
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }
}
