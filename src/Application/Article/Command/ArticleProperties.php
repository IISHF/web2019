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
     * @Assert\Type("\DateTimeImmutable")
     *
     * @var \DateTimeImmutable|null
     */
    private $publishedAt;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
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

    /**
     * @return \DateTimeImmutable
     */
    public function getPublishedAt(): \DateTimeImmutable
    {
        if (!$this->publishedAt) {
            $this->publishedAt = new \DateTimeImmutable('now');
        }
        return $this->publishedAt;
    }
}
