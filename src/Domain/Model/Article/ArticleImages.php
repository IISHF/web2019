<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 15:05
 */

namespace App\Domain\Model\Article;

/**
 * Class ArticleImages
 *
 * @package App\Domain\Model\Article
 */
class ArticleImages extends ArticleAttachments
{
    /**
     * @var ArticleImage|bool|null
     */
    private $primaryImage;

    /**
     * @var ArticleImage[]|null
     */
    private $secondaryImages;

    /**
     * @return ArticleImage|null
     */
    public function getPrimaryImage(): ?ArticleImage
    {
        if ($this->primaryImage === null) {
            $this->primaryImage = false;
            foreach ($this as $image) {
                /** @var ArticleImage $image */
                if ($image->isPrimaryImage()) {
                    $this->primaryImage = $image;
                    break;
                }
            }
        }
        if ($this->primaryImage instanceof ArticleImage) {
            return $this->primaryImage;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function hasPrimaryImage(): bool
    {
        return $this->getPrimaryImage() !== null;
    }

    /**
     * @return ArticleImage[]
     */
    public function getSecondaryImages(): array
    {
        if ($this->secondaryImages === null) {
            $this->secondaryImages = [];
            foreach ($this as $image) {
                /** @var ArticleImage $image */
                if (!$image->isPrimaryImage()) {
                    $this->secondaryImages[] = $image;
                }
            }
        }
        return $this->secondaryImages;
    }

    /**
     * @return bool
     */
    public function hasSecondaryImages(): bool
    {
        return count($this->getSecondaryImages()) > 0;
    }
}
