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
 * @ORM\Table(
 *      name="articles",
 *      indexes={
 *          @ORM\Index(name="idx_article_state_date", columns={"current_state", "published_at"})
 *      }
 *  )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=true)
 * @Gedmo\Loggable(logEntryClass="ArticleVersion")
 */
class Article
{
    use ChangeTracking, SoftDeleteableEntity;

    public const STATE_DRAFT     = 0b001;
    public const STATE_REVIEW    = 0b010;
    public const STATE_PUBLISHED = 0b100;
    public const STATE_ALL       = 0b111;

    /**
     * @var array
     */
    private static $availableStates = [
        self::STATE_DRAFT     => 'draft',
        self::STATE_REVIEW    => 'review',
        self::STATE_PUBLISHED => 'published',
    ];

    /**
     * @param int $states
     * @return string[]
     */
    public static function getStates(int $states): array
    {
        $finalStates = [];
        foreach (self::$availableStates as $state => $name) {
            if (($state & $states) === $state) {
                $finalStates[] = $name;
            }
        }
        return $finalStates;
    }

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="legacy_format", type="boolean", options={"unsigned":true})
     *
     * @var bool
     */
    private $legacyFormat;

    /**
     * @ORM\Column(name="current_state", type="string", length=16)
     *
     * @var string
     */
    private $currentState;

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
     * @ORM\Column(name="published_at", type="datetime_immutable")
     * @Gedmo\Versioned()
     *
     * @var \DateTimeImmutable
     */
    private $publishedAt;

    /**
     * @param string             $id
     * @param string             $slug
     * @param string             $title
     * @param string|null        $subtitle
     * @param string             $body
     * @param array              $tags
     * @param \DateTimeImmutable $publishedAt
     * @return Article
     */
    public static function create(
        string $id,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        \DateTimeImmutable $publishedAt
    ): self {
        return new self($id, false, $slug, $title, $subtitle, $body, $tags, $publishedAt);
    }

    /**
     * @param string             $id
     * @param string             $slug
     * @param string             $title
     * @param string|null        $subtitle
     * @param string             $body
     * @param array              $tags
     * @param \DateTimeImmutable $publishedAt
     * @return Article
     */
    public static function createLegacy(
        string $id,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        \DateTimeImmutable $publishedAt
    ): self {
        $article               = new self($id, true, $slug, $title, $subtitle, $body, $tags, $publishedAt);
        $article->currentState = self::$availableStates[self::STATE_PUBLISHED];
        return $article;
    }

    /**
     * @param string             $id
     * @param bool               $legacyFormat
     * @param string             $slug
     * @param string             $title
     * @param string|null        $subtitle
     * @param string             $body
     * @param array              $tags
     * @param \DateTimeImmutable $publishedAt
     */
    private function __construct(
        string $id,
        bool $legacyFormat,
        string $slug,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        \DateTimeImmutable $publishedAt
    ) {
        Assert::uuid($id);

        $this->id           = $id;
        $this->legacyFormat = $legacyFormat;
        $this->currentState = self::$availableStates[self::STATE_DRAFT];
        $this->setSlug($slug)
             ->setTitle($title)
             ->setSubtitle($subtitle)
             ->setBody($body)
             ->setTags($tags)
             ->setPublishedAt($publishedAt)
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
        Assert::oneOf($currentState, self::$availableStates);
        $this->currentState = $currentState;
        return $this;
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

    /**
     * @return \DateTimeImmutable
     */
    public function getPublishedAt(): \DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeImmutable $publishedAt
     * @return $this
     */
    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }
}
