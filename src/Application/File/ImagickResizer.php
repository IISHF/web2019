<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 14:24
 */

namespace App\Application\File;

use App\Domain\Model\File\File;
use Imagick;

/**
 * Class ImagickResizer
 *
 * @package App\Application\File
 */
class ImagickResizer implements ImageResizer
{
    /**
     * {@inheritdoc}
     */
    public function resizeImage(File $file, int $width, int $height): ResizedImage
    {
        $im = $this->createImage($file);
        return new ImagickResizedImage($this->resize($im, $width, $height));
    }

    /**
     * {@inheritdoc}
     */
    public function resizePdf(File $file, int $width, int $height): ResizedImage
    {
        $im = $this->createImage($file, 0);
        $im->setIteratorIndex(0);
        $im->setBackgroundColor('white');
        $im->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $im->setResolution(150.0, 150.0);
        $im->setFormat('png');
        return new ImagickResizedImage($this->resize($im, $width, $height));
    }

    /**
     * @param File     $file
     * @param int|null $page
     * @return Imagick
     */
    private function createImage(File $file, ?int $page = null): Imagick
    {
        $tmpFilename    = $file->writeTo(null);
        $fileDescriptor = $tmpFilename;
        if ($page !== null) {
            $fileDescriptor .= '[' . $page . ']';
        }
        $im = new Imagick($fileDescriptor);
        unlink($tmpFilename);
        return $im;
    }

    /**
     * @param Imagick $im
     * @param int      $width
     * @param int      $height
     * @return Imagick
     */
    private function resize(Imagick $im, int $width, int $height): Imagick
    {
        if ($width > 0 && $height > 0) {
            $ratio    = $width / $height;
            $imWidth  = $im->getImageWidth();
            $imHeight = $im->getImageHeight();
            $imRatio  = $imWidth / $imHeight;
            if ($ratio > $imRatio) {
                $newWidth  = $width;
                $newHeight = $width / $imWidth * $imHeight;
                $cropX     = 0;
                $cropY     = (int)(($newHeight - $height) / 2);
            } else {
                $newWidth  = $height / $imHeight * $imWidth;
                $newHeight = $height;
                $cropX     = (int)(($newWidth - $width) / 2);
                $cropY     = 0;
            }
            $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1.0, true);
            $im->cropImage($width, $height, $cropX, $cropY);
        } elseif ($width > 0 || $height > 0) {
            $im->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1.0);
        }
        return $im;
    }
}
