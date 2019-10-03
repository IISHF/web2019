<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 12:46
 */

namespace App\Domain\Model\Article;

use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\HasId;
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
    use HasId, AssociationOne;

    /**
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Article
     */
    private $article;

    /**
     * @ORM\OneToOne(targetEntity="\App\Domain\Model\File\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
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
        $this->setId($id)
             ->setArticle($article)
             ->setFile($file);
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        /** @var Article $article */
        $article = $this->getRelatedEntity($this->article, 'Attachment is not attached to an article.');
        return $article;
    }

    /**
     * @param Article $article
     * @return $this
     */
    private function setArticle(Article $article): self
    {
        Assert::true($article->isLegacyFormat());
        return $this->setRelatedEntity($this->article, $article);
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        /** @var File $file */
        $file = $this->getRelatedEntity($this->file, 'Attachment is not attached to a file.');
        return $file;
    }

    /**
     * @param File $file
     * @return $this
     */
    private function setFile(File $file): self
    {
        return $this->setRelatedEntity($this->file, $file);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->file->getName();
    }

    /**
     * @return string
     */
    public function getClientName(): string
    {
        return $this->file->getClientName();
    }
}
