<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-07
 * Time: 07:52
 */

namespace App\Infrastructure\File\Twig;

use App\Utils\Number;
use App\Utils\Text;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class FileExtension
 *
 * @package App\Infrastructure\File\Twig
 */
class FileExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('file_size', [$this, 'formatFileSize'], ['needs_environment' => true]),
            new TwigFilter('file_hash', [$this, 'formatFileHash'], ['needs_environment' => true]),
        ];
    }

    /**
     * @param Environment $env
     * @param int         $bytes
     * @param bool        $si
     * @return string
     */
    public function formatFileSize(Environment $env, int $bytes, bool $si = true): string
    {
        [$size, $unit] = Number::fileSize($bytes, $si);
        return \twig_number_format_filter($env, $size, 1) . ' ' . $unit;
    }

    /**
     * @param Environment $env
     * @param string      $hash
     * @return string
     */
    public function formatFileHash(Environment $env, string $hash): string
    {
        return Text::shorten($hash, 20, '…', $env->getCharset());
    }
}
