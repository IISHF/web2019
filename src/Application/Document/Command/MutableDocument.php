<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:56
 */

namespace App\Application\Document\Command;

/**
 * Trait MutableDocument
 *
 * @package App\Application\Document\Command
 */
trait MutableDocument
{
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
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;
        return $this;
    }
}
