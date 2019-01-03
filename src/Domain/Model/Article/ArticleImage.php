<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 12:49
 */

namespace App\Domain\Model\Article;

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
    /**
     * @ORM\Column(name="primary_image", type="boolean", options={"unsigned":true})
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
     * @param string      $mimeType
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function create(
        bool $primaryImage,
        string $id,
        Article $article,
        string $mimeType,
        ?string $caption
    ): self {
        return new self($id, $article, $mimeType, $primaryImage, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param string      $mimeType
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function createPrimary(string $id, Article $article, string $mimeType, ?string $caption): self
    {
        return self::create(true, $id, $article, $mimeType, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param string      $mimeType
     * @param string|null $caption
     * @return ArticleImage
     */
    public static function createSecondary(string $id, Article $article, string $mimeType, ?string $caption): self
    {
        return self::create(false, $id, $article, $mimeType, $caption);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param string      $mimeType
     * @param bool        $primaryImage
     * @param string|null $caption
     */
    protected function __construct(string $id, Article $article, string $mimeType, bool $primaryImage, ?string $caption)
    {
        parent::__construct($id, $article, $mimeType);
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
