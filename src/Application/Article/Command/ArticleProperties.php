<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:01
 */

namespace App\Application\Article\Command;

use App\Application\Article\Validator\UniqueSlug;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ArticleProperties
 *
 * @package App\Application\Article\Command
 */
trait ArticleProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z0-9-]+$/")
     * @UniqueSlug
     *
     * @var string
     */
    private $slug = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $title = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16777215)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $body = '';

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string"),
     *     @Assert\NotBlank()
     * })
     *
     * @var string[]
     */
    private $tags = [];

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
