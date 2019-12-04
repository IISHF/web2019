<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 11:18
 */

namespace App\Infrastructure\Twig;

use Twig\Environment;

/**
 * Class EmailRuntime
 *
 * @package App\Infrastructure\Twig
 */
class EmailRuntime
{
    /**
     * @param Environment $env
     * @param string      $email
     * @return string
     */
    public function formatSafeEmail(Environment $env, string $email): string
    {
        return sprintf(
            '<a email="%s" is="safe-email">email</a>',
            \twig_escape_filter(
                $env,
                strrev(
                    str_replace(['@', '.'], [' [at] ', ' [dot] '], $email)
                ),
                'html_attr'
            )
        );
    }
}
