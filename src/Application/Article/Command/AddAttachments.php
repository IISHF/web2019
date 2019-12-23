<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:37
 */

namespace App\Application\Article\Command;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddAttachments
 *
 * @package App\Application\Article\Command
 */
class AddAttachments implements IteratorAggregate, Countable
{
    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $articleId;

    /**
     * @var array<AddImage|AddDocument>
     */
    private $attachments = [];

    /**
     * @param string $articleId
     * @return AddAttachments
     */
    public static function create(string $articleId): self
    {
        return new self($articleId);
    }

    /**
     * @param string $articleId
     */
    private function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @param bool         $primaryImage
     * @param SplFileInfo $image
     * @param string|null  $caption
     * @return $this
     */
    public function addImage(bool $primaryImage, SplFileInfo $image, ?string $caption): self
    {
        $this->attachments[] = AddImage::add($primaryImage, $this->articleId, $image, $caption);
        return $this;
    }

    /**
     * @param SplFileInfo $image
     * @param string|null  $caption
     * @return $this
     */
    public function addPrimaryImage(SplFileInfo $image, ?string $caption): self
    {
        return $this->addImage(true, $image, $caption);
    }

    /**
     * @param SplFileInfo $image
     * @param string|null  $caption
     * @return $this
     */
    public function addSecondaryImage(SplFileInfo $image, ?string $caption): self
    {
        return $this->addImage(false, $image, $caption);
    }

    /**
     * @param SplFileInfo $document
     * @param string|null  $title
     * @return $this
     */
    public function addDocument(SplFileInfo $document, ?string $title): self
    {
        $this->attachments[] = AddDocument::add($this->articleId, $document, $title);
        return $this;
    }

    /**
     * @return string
     */
    public function getArticleId(): string
    {
        return $this->articleId;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->attachments);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->attachments);
    }
}
