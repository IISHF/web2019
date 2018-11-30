<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:07
 */

namespace App\Domain\Model\Article;

use App\Domain\Model\Common\ChangeTracking;
use App\Domain\Model\Common\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Webmozart\Assert\Assert;

/**
 * Class Article
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity(repositoryClass="ArticleRepository")
 * @ORM\Table(name="articles")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable(logEntryClass="ArticleVersion")
 */
class Article
{
    use ChangeTracking, SoftDeleteableEntity;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     * @Gedmo\Versioned()
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="title", type="string", length=128)
     * @Gedmo\Versioned()
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="body", type="text", length=16777215)
     * @Gedmo\Versioned()
     *
     * @var string
     */
    private $body;

    /**
     * @ORM\Column(name="tags", type="json")
     * @Gedmo\Versioned()
     *
     * @var string[]
     */
    private $tags;

    /**
     * @param string $id
     * @param string $slug
     * @param string $title
     * @param string $body
     * @param array  $tags
     */
    public function __construct(string $id, string $slug, string $title, string $body, array $tags = [])
    {
        Assert::uuid($id);

        $this->id = $id;
        $this->setSlug($slug)
             ->setTitle($title)
             ->setBody($body)
             ->setTags($tags)
             ->initChangeTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        Assert::regex($slug, '/^[0-9a-z-]+$/');
        Assert::lengthBetween($slug, 1, 128);
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        Assert::lengthBetween($title, 1, 128);
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        Assert::lengthBetween($body, 1, 65535);
        $this->body = $body;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = array_unique($tags);
        return $this;
    }
}
