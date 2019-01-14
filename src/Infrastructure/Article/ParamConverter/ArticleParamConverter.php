<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:20
 */

namespace App\Infrastructure\Article\ParamConverter;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleParamConverter
 *
 * @package App\Infrastructure\Article\ParamConverter
 */
class ArticleParamConverter extends LoaderParamConverter
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Article::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if (Uuid::isValid($value)) {
            return $this->articleRepository->findById($value);
        }
        return $this->articleRepository->findBySlug($value);
    }
}
