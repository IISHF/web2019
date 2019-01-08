<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 13:48
 */

namespace App\Application\Article;

/**
 * Class ArticleBody
 *
 * @package App\Application\Article
 */
final class ArticleBody
{
    private const FILE_PATTERN = '/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})/';

    /**
     */
    private function __construct()
    {
    }

    /**
     * @param string $body
     * @return string[]
     */
    public static function findAttachments(string $body): array
    {
        $matches     = [];
        $attachments = [];
        preg_match_all('/data-trix-attachment="([^"]+)"/', $body, $matches);
        foreach ($matches[1] as $match) {
            $match            = html_entity_decode($match, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $attachmentConfig = @json_decode($match, true);
            if (!is_array($attachmentConfig) || json_last_error() > JSON_ERROR_NONE) {
                continue;
            }
            $url        = $attachmentConfig['url'] ?? $attachmentConfig['href'] ?? null;
            $urlMatches = [];
            if (!$url || !preg_match(self::FILE_PATTERN, $url, $urlMatches)) {
                continue;
            }
            $attachments[] = $urlMatches[1];
        }
        return $attachments;
    }
}
