<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:07
 */

namespace App\Domain\Model\Article;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\SoftDeleteableEntity;
use App\Domain\Model\Common\UpdateTracking;
use Collator;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Webmozart\Assert\Assert;

/**
 * Class Article
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity(repositoryClass="ArticleRepository")
 * @ORM\Table(
 *      name="articles",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_article_slug", columns={"slug"})
 *      },
 *      indexes={
 *          @ORM\Index(name="idx_article_state_date", columns={"current_state", "published_at"})
 *      }
 *  )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable(logEntryClass="ArticleVersion")
 */
class Article
{
    use HasId, CreateTracking, UpdateTracking, SoftDeleteableEntity;

    public const  FILE_ORIGIN  = 'com.iishf.article';
    private const FILE_PATTERN = '/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})/';

    public const STATE_DRAFT     = 'draft';
    public const STATE_PUBLISHED = 'published';

    /**
     * @ORM\Column(name="legacy_format", type="boolean")
     *
     * @var bool
     */
    private $legacyFormat = false;

    /**
     * @ORM\Column(name="current_state", type="string", length=16)
     *
     * @var string
     */
    private $currentState;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
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
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     * @Gedmo\Versioned()
     *
     * @var string|null
     */
    private $subtitle;

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
     * @ORM\Column(name="author", type="string", length=128)
     *
     * @var string
     */
    private $author;

    /**
     * @ORM\Column(name="published_at", type="datetime_immutable", nullable=true)
     *
     * @var DateTimeImmutable|null
     */
    private $publishedAt;

    /**
     * @param string      $id
     * @param string      $slug
     * @param string      $title
     * @param string|null $subtitle
     * @param string      $body
     * @param array       $tags
     * @param string      $author
     * @return Article
     */
    public static function create(
        string $id,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        string $author
    ): self {
        return new self($id, $slug, $title, $subtitle, $body, $tags, $author);
    }

    /**
     * @param string             $id
     * @param string             $slug
     * @param string             $title
     * @param string|null        $subtitle
     * @param string             $body
     * @param array              $tags
     * @param string             $author
     * @param DateTimeImmutable $publishedAt
     * @return Article
     */
    public static function createLegacy(
        string $id,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        string $author,
        DateTimeImmutable $publishedAt
    ): self {
        $article               = new self($id, $slug, $title, $subtitle, $body, $tags, $author);
        $article->legacyFormat = true;
        $article->currentState = self::STATE_PUBLISHED;
        $article->publishedAt  = $publishedAt;
        return $article;
    }

    /**
     * @param string      $id
     * @param string      $slug
     * @param string      $title
     * @param string|null $subtitle
     * @param string      $body
     * @param array       $tags
     * @param string      $author
     */
    private function __construct(
        string $id,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        string $author
    ) {
        $this->setId($id)
             ->setSlug($slug)
             ->setTitle($title)
             ->setSubtitle($subtitle)
             ->setBody($body)
             ->setTags($tags)
             ->setAuthor($author)
             ->initCreateTracking()
             ->initUpdateTracking();
        $this->currentState = self::STATE_DRAFT;
    }

    /**
     * @return bool
     */
    public function isLegacyFormat(): bool
    {
        return $this->legacyFormat;
    }

    /**
     * @return string
     */
    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    /**
     * @param string $currentState
     * @return $this
     */
    public function setCurrentState(string $currentState): self
    {
        Assert::lengthBetween($currentState, 1, 16);
        $this->currentState = $currentState;
        if (!$this->isPublished()) {
            $this->publishedAt = null;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->currentState === self::STATE_PUBLISHED;
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
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * @param string|null $subtitle
     * @return $this
     */
    public function setSubtitle(?string $subtitle): self
    {
        Assert::nullOrLengthBetween($subtitle, 1, 255);
        $this->subtitle = $subtitle;
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
     * @return string|null
     */
    public function getFirstAttachmentUrl(): ?string
    {
        $attachments = $this->getAttachments(true);
        if (empty($attachments)) {
            return null;
        }
        return reset($attachments);
    }

    /**
     * @param bool $returnUrl
     * @return array
     */
    public function getAttachments(bool $returnUrl = true): array
    {
        if ($this->isLegacyFormat()) {
            return [];
        }
        return self::findAttachments($this->body, $returnUrl);
    }

    /**
     * @param string $body
     * @param bool   $returnUrl
     * @return array
     */
    public static function findAttachments(string $body, bool $returnUrl = true): array
    {
        $matches     = [];
        $attachments = [];
        preg_match_all('/data-trix-attachment="([^"]+)"/', $body, $matches);
        foreach ($matches[1] as $match) {
            $match            = html_entity_decode($match, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $attachmentConfig = @json_decode($match, true);
            if (!is_array($attachmentConfig) || json_last_error() > JSON_ERROR_NONE) {
                continue;
            }
            $url        = $attachmentConfig['url'] ?? $attachmentConfig['href'] ?? null;
            $urlMatches = [];
            if (!$url || !preg_match(self::FILE_PATTERN, $url, $urlMatches)) {
                continue;
            }
            $attachments[] = $returnUrl ? $url : $urlMatches[1];
        }
        return $attachments;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        Assert::lengthBetween($body, 1, 16777215);
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
        $collator = Collator::create('en-US');
        $collator->sort($tags);
        $this->tags = array_unique($tags);
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author): self
    {
        Assert::lengthBetween($author, 1, 128);
        $this->author = $author;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPublishedAt(): ?DateTimeImmutable
    {
        if ($this->publishedAt === null && $this->isPublished()) {
            return new DateTimeImmutable('now');
        }
        return $this->publishedAt;
    }

    /**
     * @param DateTimeImmutable $publishedAt
     * @return $this
     */
    public function setPublishedAt(DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }
}
