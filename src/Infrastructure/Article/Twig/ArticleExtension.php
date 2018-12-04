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
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'article_body_format',
                [ArticleRuntime::class, 'formatArticleBody'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }
}
