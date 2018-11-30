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
            $article->getBody(),
            $article->getTags()
        );
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $body
     * @param array  $tags
     */
    private function __construct(
        string $id,
        string $title,
        string $body,
        array $tags
    ) {
        $this->id    = $id;
        $this->title = $title;
        $this->body  = $body;
        $this->tags  = $tags;
    }
}
