<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:07
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Article\Article;

/**
 * Class UpdateArticle
 *
 * @package App\Application\Article\Command
 */
class UpdateArticle
{
    use UuidAware, MutableArticle, ArticleProperties;

    /**
     * @param Article $article
     * @return self
     */
    public static function update(Article $article): self
    {
        return new self(
            $article->getId(),
            $article->getTitle(),
            $article->getSubtitle(),
            $article->getBody(),
            $article->getTags(),
            $article->getPublishedAt()
        );
    }

    /**
     * @param string             $id
     * @param string             $title
     * @param string|null        $subtitle
     * @param string             $body
     * @param array              $tags
     * @param \DateTimeImmutable $publishedAt
     */
    private function __construct(
        string $id,
        string $title,
        ?string $subtitle,
        string $body,
        array $tags,
        \DateTimeImmutable $publishedAt
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->subtitle    = $subtitle;
        $this->body        = $body;
        $this->tags        = $tags;
        $this->publishedAt = $publishedAt;
    }
}
