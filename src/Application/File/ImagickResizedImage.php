<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 14:21
 */

namespace App\Application\File;

use Imagick;

/**
 * Class ImagickResizedImage
 *
 * @package App\Application\File
 */
class ImagickResizedImage implements ResizedImage
{
    /**
     * @var Imagick
     */
    private $im;

    /**
     * @param Imagick $im
     */
    public function __construct(Imagick $im)
    {
        $this->im = $im;
    }

    /**
     *
     */
    public function __destruct()
    {
        if ($this->im) {
            $this->im->destroy();
            $this->im = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->im->getImageBlob();
    }
}
