<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 10:37
 */

namespace App\Infrastructure\Article\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class ArticleExtension
 *
 * @package App\Infrastructure\Article\Twig
 */
class ArticleExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
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
            new TwigFilter(
                'article_legacy_body',
                [ArticleRuntime::class, 'formatLegacyBody'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }
}
