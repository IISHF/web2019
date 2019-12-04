<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 11:07
 */

namespace App\Infrastructure\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class EmailExtension
 *
 * @package App\Infrastructure\Twig
 */
class EmailExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'safe_email',
                [EmailRuntime::class, 'formatSafeEmail'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }
}
