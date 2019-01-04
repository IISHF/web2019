<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 14:23
 */

namespace App\Application\File;

use Psr\Cache\CacheItemInterface;

/**
 * Class CachedResizedImage
 *
 * @package App\Application\File
 */
class CachedResizedImage implements ResizedImage
{
    /**
     * @var CacheItemInterface
     */
    private $cacheItem;

    /**
     * CachedResizedImage constructor.
     *
     * @param CacheItemInterface $cacheItem
     */
    public function __construct(CacheItemInterface $cacheItem)
    {
        $this->cacheItem = $cacheItem;
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->cacheItem) {
            $this->cacheItem = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string)$this->cacheItem->get();
    }
}
