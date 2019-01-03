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
 * Class ArticleDocument
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity()
 */
class ArticleDocument extends ArticleAttachment
{
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $title;

    /**
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param string|null $title
     * @return ArticleDocument
     */
    public static function create(string $id, Article $article, File $file, ?string $title): self
    {
        return new self($id, $article, $file, $title);
    }

    /**
     * @param string      $id
     * @param Article     $article
     * @param File        $file
     * @param string|null $title
     */
    protected function __construct(string $id, Article $article, File $file, ?string $title)
    {
        parent::__construct($id, $article, $file);
        $this->setTitle($title);
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        Assert::nullOrLengthBetween($title, 1, 255);
        $this->title = $title;
        return $this;
    }
}
