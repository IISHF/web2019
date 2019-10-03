<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:06
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\IdAware;

/**
 * Class CreateArticle
 *
 * @package App\Application\Article\Command
 */
class CreateArticle
{
    use IdAware, ArticleAuthor, ArticleProperties;

    /**
     * @param string $author
     * @return self
     */
    public static function create(string $author): self
    {
        return new self(self::createUuid(), $author);
    }

    /**
     * @param string $id
     * @param string $author
     */
    private function __construct(string $id, string $author)
    {
        $this->id     = $id;
        $this->author = $author;
    }
}
