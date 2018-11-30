<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:14
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;

/**
 * Trait ArticleAware
 *
 * @package App\Application\Article\Command
 */
trait ArticleAware
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }
}
