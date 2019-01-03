<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 12:46
 */

namespace App\Domain\Model\Article;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class ArticleAttachment
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity()
 * @ORM\Table(name="article_attachments")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=16)
 * @ORM\DiscriminatorMap({"image" = "ArticleImage", "document" = "ArticleDocument"})
 */
abstract class ArticleAttachment
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Article
     */
    private $article;

    /**
     * @ORM\Column(name="mime_type", type="string", length=64)
     *
     * @var string
     */
    private $mimeType;

    /**
     * @param string  $id
     * @param Article $article
     * @param string  $mimeType
     */
    protected function __construct(string $id, Article $article, string $mimeType)
    {
        Assert::uuid($id);
        Assert::true($article->isLegacyFormat());
        Assert::lengthBetween($mimeType, 1, 64);

        $this->id       = $id;
        $this->article  = $article;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
