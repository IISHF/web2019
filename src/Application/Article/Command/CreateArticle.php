<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:06
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateArticle
 *
 * @package App\Application\Article\Command
 */
class CreateArticle
{
    use UuidAware, ArticleProperties, MutableArticle;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Email
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $author;

    /**
     * @var bool
     */
    private $legacyFormat;

    /**
     * @param string $author
     * @return self
     */
    public static function create(string $author): self
    {
        return new self(self::createUuid(), $author, false);
    }

    /**
     * @param string $author
     * @return self
     */
    public static function createLegacy(string $author): self
    {
        return new self(self::createUuid(), $author, true);
    }

    /**
     * @param string $id
     * @param string $author
     * @param bool   $legacyFormat
     */
    private function __construct(string $id, string $author, bool $legacyFormat)
    {
        $this->id           = $id;
        $this->author       = $author;
        $this->legacyFormat = $legacyFormat;
        $this->publishedAt  = new \DateTimeImmutable('now');
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLegacyFormat(): bool
    {
        return $this->legacyFormat;
    }
}
