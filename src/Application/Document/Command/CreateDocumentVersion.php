<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateDocumentVersion
 *
 * @package App\Application\Document\Command
 */
class CreateDocumentVersion
{
    use UuidAware, MutableDocumentVersion, DocumentVersionProperties;

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
     * @param string $documentId
     * @return self
     */
    public static function create(string $documentId): self
    {
        return new self(self::createUuid(), $documentId);
    }

    /**
     * @param string $id
     * @param string $documentId
     */
    private function __construct(string $id, string $documentId)
    {
        $this->id         = $id;
        $this->documentId = $documentId;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo $file
     * @return $this
     */
    public function setFile(\SplFileInfo $file): self
    {
        $this->file = $file;
        return $this;
    }
}
