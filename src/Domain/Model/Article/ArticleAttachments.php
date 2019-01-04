<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 15:09
 */

namespace App\Domain\Model\Article;

/**
 * Class ArticleAttachments
 *
 * @package App\Domain\Model\Article
 */
abstract class ArticleAttachments implements \Countable, \IteratorAggregate
{
    /**
     * @var ArticleAttachment[]
     */
    private $attachments;

    /**
     * @return static
     */
    public static function empty(): self
    {
        return new static([]);
    }

    /**
     * @param ArticleAttachment[] $attachments
     */
    public function __construct(array $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->attachments);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->attachments);
    }
}
