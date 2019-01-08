<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:12
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddDocument
 *
 * @package App\Application\Article\Command
 */
class AddDocument
{
    use UuidAware, AttachmentProperties;

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
     * @Assert\Type("SplFileInfo")
     * @Assert\NotNull()
     *
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     * @var string|null
     */
    private $title;

    /**
     * @param string       $articleId
     * @param \SplFileInfo $document
     * @param string|null  $title
     * @return AddDocument
     */
    public static function add(string $articleId, \SplFileInfo $document, ?string $title): self
    {
        return new self(self::createUuid(), $articleId, $document, $title);
    }

    /**
     * @param string       $id
     * @param string       $articleId
     * @param \SplFileInfo $document
     * @param string|null  $title
     */
    private function __construct(string $id, string $articleId, \SplFileInfo $document, ?string $title)
    {
        $this->id        = $id;
        $this->articleId = $articleId;
        $this->file      = $document;
        $this->title     = $title;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
