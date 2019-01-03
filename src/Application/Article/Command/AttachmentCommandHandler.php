<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:21
 */

namespace App\Application\Article\Command;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * Class AttachmentCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class AttachmentCommandHandler extends ArticleCommandHandler
{
    /**
     * @param \SplFileInfo $file
     * @param string       $default
     * @return string
     */
    protected static function guessMimeType(\SplFileInfo $file, string $default = 'application/octet-stream'): string
    {
        return MimeTypeGuesser::getInstance()->guess($file->getPathname()) ?? $default;
    }
}
