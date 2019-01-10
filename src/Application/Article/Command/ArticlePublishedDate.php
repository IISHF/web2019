<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 11:06
 */

namespace App\Application\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ArticlePublishedDate
 *
 * @package App\Application\Article\Command
 */
trait ArticlePublishedDate
{
    /**
     * @Assert\Type("\DateTimeImmutable")
     *
     * @var \DateTimeImmutable|null
     */
    private $publishedAt;

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeImmutable $publishedAt
     * @return $this
     */
    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }
}
