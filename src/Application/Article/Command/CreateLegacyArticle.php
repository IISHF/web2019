<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 10:57
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateLegacyArticle
 *
 * @package App\Application\Article\Command
 */
class CreateLegacyArticle
{
    use UuidAware, ArticleAuthor, ArticlePublishedDate, ArticleProperties;

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
        $this->id          = $id;
        $this->author      = $author;
        $this->publishedAt = new \DateTimeImmutable('now');
    }
}
