<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:07
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;

/**
 * Class UpdateArticle
 *
 * @package App\Application\Article\Command
 */
class UpdateArticle
{
    use ArticleAware, MutableArticle, ArticleProperties;

    /**
     * @param Article $article
     * @return self
     */
    public static function update(Article $article): self
    {
        return new self(
            $article,
            $article->getTitle(),
            $article->getBody(),
            $article->getTags()
        );
    }

    /**
     * @param Article $article
     * @param string  $title
     * @param string  $body
     * @param array   $tags
     */
    private function __construct(
        Article $article,
        string $title,
        string $body,
        array $tags
    ) {
        $this->article = $article;
        $this->title   = $title;
        $this->body    = $body;
        $this->tags    = $tags;
    }
}
