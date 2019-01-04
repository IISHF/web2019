<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 10:37
 */

namespace App\Infrastructure\Article\Twig;

/**
 * Class ArticleExtension
 *
 * @package App\Infrastructure\Article\Twig
 */
class ArticleExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction(
                'article_author',
                [ArticleRuntime::class, 'renderArticleAuthor'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter(
                'article_legacy_body',
                [ArticleRuntime::class, 'formatLegacyBody'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }
}
