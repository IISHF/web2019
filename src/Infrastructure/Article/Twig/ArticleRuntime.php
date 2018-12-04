<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 10:38
 */

namespace App\Infrastructure\Article\Twig;

use App\Domain\Model\Article\Article;
use App\Utils\Text;

/**
 * Class ArticleRuntime
 *
 * @package App\Infrastructure\Article\Twig
 */
class ArticleRuntime
{
    private const REGEX_EMAIL = '/\b([a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+)\b/ixu';
    private const REGEX_URL   = '~\b
            ((?P<protocol>https?)://)                                         # protocol
            (?P<host>
                    (
                    ([\pL\pN\pS\-\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?)    # a domain name
                        |                                                     # or
                    \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                        # an IP address
                        |                                                     # or
                    \[
                        (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                    \]                                                        # an IPv6 address
                )
                (:[0-9]+)?                                                    # a port (optional)
            ) 
            (?P<address>
                (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*    # a path
                (?:\? (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )? # a query (optional)
                (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )? # a fragment (optional)
            )
            \b~ixu';

    /**
     * @param \Twig_Environment $env
     * @param Article           $article
     * @return string
     */
    public function formatArticleBody(\Twig_Environment $env, Article $article): string
    {
        $body = $article->getBody();

        if (!$article->isLegacyFormat()) {
            return $body;
        }

        $body = str_replace("\r", '', $body);

        $body = preg_replace_callback(
            self::REGEX_URL,
            function (array $matches): string {
                if (empty($matches['protocol'])) {
                    $matches['protocol'] = 'http';
                }

                $href = $matches['protocol'] . '://' . $matches['host'] . $matches['address'];
                $text = $matches['host'] . $matches['address'];

                return "\x1F" . base64_encode($href) . "\x1F" . base64_encode($text) . "\x1F";
            },
            $body
        );
        $body = preg_replace_callback(
            self::REGEX_EMAIL,
            function (array $matches): string {
                $email = $matches[1];
                return "\x1E" . base64_encode($email) . "\x1E";
            },
            $body
        );


        $escape = function (string $text, string $strategy = 'html') use ($env): string {
            return \twig_escape_filter($env, $text, $strategy);
        };

        $body = $escape($body);

        $body = preg_replace_callback(
            '/\x1E([a-z0-9+\/=]+)\x1E/i',
            function (array $matches) use ($escape): string {
                $mail = base64_decode($matches[1]);
                $text = $escape(Text::shorten($mail, 32));
                $mail = $escape($mail, 'html_attr');
                return <<<HTML
<a href="mailto:$mail" data-toggle="tooltip" data-placement="top" title="$mail">$text</a>
HTML;
            },
            $body
        );
        $body = preg_replace_callback(
            '/\x1F([a-z0-9+\/=]+)\x1F([a-z0-9+\/=]+)\x1F/i',
            function (array $matches) use ($escape): string {
                [, $href, $text] = $matches;
                $text = $escape(Text::shorten(base64_decode($text), 32));
                $href = $escape(base64_decode($href), 'html_attr');
                return <<<HTML
<a href="$href" target="_blank" rel="noopener" referrerpolicy="origin-when-cross-origin" data-toggle="tooltip" data-placement="top" title="$href">$text</a>
HTML;
            },
            $body
        );
        return nl2br($body);
    }
}
