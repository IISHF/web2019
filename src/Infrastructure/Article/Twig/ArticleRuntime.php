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
    private const REGEX_EMAIL = '/\b(.+\@\S+\.\S+)\b/ixu';
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
            )\b~ixu';

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

        $body = preg_replace(self::REGEX_EMAIL, "\x1E" . '$1' . "\x1E", $body);
        $body = preg_replace_callback(
            self::REGEX_URL,
            function (array $matches): string {
                if (empty($matches['protocol'])) {
                    $matches['protocol'] = 'http';
                }

                $href = $matches['protocol'] . '://' . $matches['host'] . $matches['address'];
                $text = $matches['host'] . $matches['address'];

                return "\x1F" . $href . "\x1F" . $text . "\x1F";
            },
            $body
        );

        $body = \twig_escape_filter($env, $body, 'html');

        $body = preg_replace_callback(
            '/\x1E(.+)\x1E/',
            function (array $matches): string {
                $mail = $matches[1];
                $text = Text::shorten($mail, 32);
                return <<<HTML
<a href="mailto:$mail" data-toggle="tooltip" data-placement="top" title="$mail">$text</a>
HTML;
            },
            $body
        );
        $body = preg_replace_callback(
            '/\x1F(.+)\x1F(.+)\x1F/',
            function (array $matches): string {
                [, $href, $text] = $matches;
                $text = Text::shorten($text, 32);
                return <<<HTML
<a href="$href" target="_blank" rel="noopener" referrerpolicy="origin-when-cross-origin" data-toggle="tooltip" data-placement="top" title="$href">$text</a>
HTML;
            },
            $body
        );
        return nl2br($body);
    }
}
