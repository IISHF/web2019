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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    private $repository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
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
    protected function loadObject($value, ParamConverter $configuration): ?object
    {
        return $this->repository->findBySlug($value);
    }
}
