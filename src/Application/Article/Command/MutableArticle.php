<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:04
 */

namespace App\Application\Article\Command;

use App\Domain\Common\Urlizer;

/**
 * Trait MutableArticle
 *
 * @package App\Application\Article\Command
 */
trait MutableArticle
{
    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        if (empty($this->slug)) {
            $this->setSlug(Urlizer::urlize($title));
        }
        return $this;
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
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }

}
