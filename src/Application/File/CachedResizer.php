<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 14:24
 */

namespace App\Application\File;

use App\Domain\Model\File\File;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CachedResizer
 *
 * @package App\Application\File
 */
class CachedResizer implements ImageResizer
{
    /**
     * @var ImageResizer
     */
    private $imageResizer;
    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * @param ImageResizer           $imageResizer
     * @param CacheItemPoolInterface $cacheItemPool
     */
    public function __construct(ImageResizer $imageResizer, CacheItemPoolInterface $cacheItemPool)
    {
        $this->imageResizer  = $imageResizer;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function resizeImage(File $file, int $width, int $height): ResizedImage
    {
        return $this->checkCache(
            $file,
            $width,
            $height,
            function () use ($file, $width, $height) {
                return $this->imageResizer->resizeImage($file, $width, $height);
            }
        );
    }

    /**
     * {@inheritdoc}
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function resizePdf(File $file, int $width, int $height): ResizedImage
    {
        return $this->checkCache(
            $file,
            $width,
            $height,
            function () use ($file, $width, $height) {
                return $this->imageResizer->resizePdf($file, $width, $height);
            }
        );
    }

    /**
     * @param File     $file
     * @param int      $width
     * @param int      $height
     * @param callable $fn
     * @return CachedResizedImage
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function checkCache(File $file, int $width, int $height, callable $fn): CachedResizedImage
    {
        $cacheKey = 'image_' . sha1($file->getId() . '/w=' . $width . '/h=' . $height);
        $cached   = $this->cacheItemPool->getItem($cacheKey);
        if (!$cached->isHit()) {
            $resized = $fn();
            $cached->set((string)$resized);
            $this->cacheItemPool->save($cached);
        }
        return new CachedResizedImage($cached);
    }
}
