<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 12:46
 */

namespace App\Domain\Model\Article;

use App\Domain\Model\File\File;
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
     * @ORM\OneToOne(targetEntity="\App\Domain\Model\File\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var File
     */
    private $file;

    /**
     * @param string  $id
     * @param Article $article
     * @param File    $file
     */
    protected function __construct(string $id, Article $article, File $file)
    {
        Assert::uuid($id);
        Assert::true($article->isLegacyFormat());

        $this->id      = $id;
        $this->article = $article;
        $this->file    = $file;
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
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getFile()->getName();
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->getFile()->getClientName();
    }
}
