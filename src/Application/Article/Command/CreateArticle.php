<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:06
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateArticle
 *
 * @package App\Application\Article\Command
 */
class CreateArticle
{
    use UuidAware, MutableArticle, ArticleProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::uuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
