<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-07
 * Time: 07:52
 */

namespace App\Infrastructure\File\Twig;

use App\Utils\Text;

/**
 * Class FileExtension
 *
 * @package App\Infrastructure\File\Twig
 */
class FileExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('file_size', [$this, 'formatFileSize'], ['needs_environment' => true]),
            new \Twig_SimpleFilter('file_hash', [$this, 'formatFileHash'], ['needs_environment' => true]),
        ];
    }

    /**
     * @param \Twig_Environment $env
     * @param int               $bytes
     * @param bool              $si
     * @return string
     */
    public function formatFileSize(\Twig_Environment $env, int $bytes, bool $si = true): string
    {
        $unit = $si ? 1000 : 1024;
        if ($bytes <= $unit) {
            return $bytes . ' B';
        }
        $exp = (int)(log($bytes) / log($unit));
        $pre = ($si ? 'kMGTPE' : 'KMGTPE');
        $pre = $pre[$exp - 1] . ($si ? '' : 'i');

        return \twig_number_format_filter($env, $bytes / ($unit ** $exp), 1) . sprintf(' %sB', $pre);
    }

    /**
     * @param \Twig_Environment $env
     * @param string            $hash
     * @return string
     */
    public function formatFileHash(\Twig_Environment $env, string $hash): string
    {
        return Text::shorten($hash, 20, '…', $env->getCharset());
    }
}
