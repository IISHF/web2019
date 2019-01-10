<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:01
 */

namespace App\Application\Article\Command;

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
     *
     * @var string
     */
    private $title = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     * @var string|null
     */
    private $subtitle = '';

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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * @param string|null $subtitle
     * @return $this
     */
    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
}
