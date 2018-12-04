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
     * @var bool
     */
    private $legacyFormat;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid(), false);
    }

    /**
     * @return self
     */
    public static function createLegacy(): self
    {
        return new self(self::createUuid(), true);
    }

    /**
     * @param string $id
     * @param bool   $legacyFormat
     */
    private function __construct(string $id, bool $legacyFormat)
    {
        $this->id           = $id;
        $this->legacyFormat = $legacyFormat;
        $this->publishedAt  = new \DateTimeImmutable('now');
    }

    /**
     * @return bool
     */
    public function isLegacyFormat(): bool
    {
        return $this->legacyFormat;
    }
}
