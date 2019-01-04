<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 14:19
 */

namespace App\Application\File;

use App\Domain\Model\File\File;

/**
 * Interface ImageResizer
 *
 * @package App\Application\File
 */
interface ImageResizer
{
    /**
     * @param File $file
     * @param int  $width
     * @param int  $height
     * @return ResizedImage
     */
    public function resizeImage(File $file, int $width, int $height): ResizedImage;

    /**
     * @param File $file
     * @param int  $width
     * @param int  $height
     * @return ResizedImage
     */
    public function resizePdf(File $file, int $width, int $height): ResizedImage;
}
