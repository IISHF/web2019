<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 12:49
 */

namespace App\Domain\Model\Article;

use App\Domain\Model\File\File;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class ArticleImage
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity()
 */
class ArticleImage extends ArticleAttachment
{
    public const FILE_ORIGIN = 'com.iishf.article.legacy.image';

    /**
     * @ORM\Column(name="primary_image", type="boolean")
     *
     * @var bool
     */
    private $primaryImage;

    /**
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $caption;

    /**
     * @param bool        $primaryImage
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function create(
        bool $primaryImage,
        string $id,
        Article $article,
        File $file,
        ?string $caption
    ): self {
        return new self($id, $article, $file, $primaryImage, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function createPrimary(string $id, Article $article, File $file, ?string $caption): self
    {
        return self::create(true, $id, $article, $file, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function createSecondary(string $id, Article $article, File $file, ?string $caption): self
    {
        return self::create(false, $id, $article, $file, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param bool        $primaryImage
     * @param string|null $caption
     */
    protected function __construct(string $id, Article $article, File $file, bool $primaryImage, ?string $caption)
    {
        parent::__construct($id, $article, $file);
        $this->primaryImage = $primaryImage;
        $this->setCaption($caption);
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
     * @return bool
     */
    public function isLowResIISHFLogo(): bool
    {
        return $this->getFile()->getBinary()->getHash() === 'c2645d57745b614b768ade942bacb4f295989444';
    }

    /**
     * @param string|null $caption
     * @return $this
     */
    public function setCaption(?string $caption): self
    {
        Assert::nullOrLengthBetween($caption, 1, 255);
        $this->caption = $caption;
        return $this;
    }
}
