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
 * Class AddImage
 *
 * @package App\Application\Article\Command
 */
class AddImage
{
    use UuidAware, AttachmentProperties;

    /**
     * @var bool
     */
    private $primaryImage;

    /**
     * @Assert\File(
     *      maxSize="2M",
     *      mimeTypes={
     *          "image/*"
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
    private $caption;

    /**
     * @param bool         $primaryImage
     * @param string       $articleId
     * @param \SplFileInfo $image
     * @param string|null  $caption
     * @return AddImage
     */
    public static function add(bool $primaryImage, string $articleId, \SplFileInfo $image, ?string $caption): self
    {
        return new self(self::createUuid(), $articleId, $image, $primaryImage, $caption);
    }

    /**
     * @param string       $articleId
     * @param \SplFileInfo $image
     * @param string|null  $caption
     * @return AddImage
     */
    public static function addPrimary(string $articleId, \SplFileInfo $image, ?string $caption): self
    {
        return self::add(true, $articleId, $image, $caption);
    }

    /**
     * @param string       $articleId
     * @param \SplFileInfo $image
     * @param string|null  $caption
     * @return AddImage
     */
    public static function addSecondary(string $articleId, \SplFileInfo $image, ?string $caption): self
    {
        return self::add(false, $articleId, $image, $caption);
    }

    /**
     * @param string       $id
     * @param string       $articleId
     * @param \SplFileInfo $image
     * @param bool         $primaryImage
     * @param string|null  $caption
     */
    private function __construct(
        string $id,
        string $articleId,
        \SplFileInfo $image,
        bool $primaryImage,
        ?string $caption
    ) {
        $this->id           = $id;
        $this->articleId    = $articleId;
        $this->primaryImage = $primaryImage;
        $this->file         = $image;
        $this->caption      = $caption;
    }

    /**
     * @return bool
     */
    public function isPrimaryImage(): bool
    {
        return $this->primaryImage;
    }

    /**
     * @return string|null
     */
    public function getCaption(): ?string
    {
        return $this->caption;
    }

    /**
     * @param string|null $caption
     * @return $this
     */
    public function setCaption(?string $caption): AddImage
    {
        $this->caption = $caption;
        return $this;
    }
}
