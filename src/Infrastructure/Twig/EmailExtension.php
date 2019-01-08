<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 11:07
 */

namespace App\Infrastructure\Twig;

/**
 * Class EmailExtension
 *
 * @package App\Infrastructure\Twig
 */
class EmailExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter(
                'safe_email',
                [EmailRuntime::class, 'formatSafeEmail'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }
}
