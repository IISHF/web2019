<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:26
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Article\Article;

/**
 * Class DeleteArticle
 *
 * @package App\Application\Article\Command
 */
class DeleteArticle
{
    use IdAware;

    /**
     * @param Article $article
     * @return self
     */
    public static function delete(Article $article): self
    {
        return new self($article->getId());
    }
}
