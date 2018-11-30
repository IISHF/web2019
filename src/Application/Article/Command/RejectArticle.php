<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 14:23
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;

/**
 * Class RejectArticle
 *
 * @package App\Application\Article\Command
 */
class RejectArticle
{
    use ArticleAware;

    /**
     * @param Article $article
     * @return self
     */
    public static function reject(Article $article): self
    {
        return new self($article);
    }

    /**
     * @param Article $article
     */
    private function __construct(Article $article)
    {
        $this->article = $article;
    }
}
