<?php

namespace App\Controller;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Infrastructure\Controller\PagingRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewsController
 *
 * @package App\Controller
 *
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request           $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $paging = PagingRequest::create($request);
        return $this->render(
            'news/index.html.twig',
            [
                'articles' => $articleRepository->findPublishedPaged($paging->getPage(), $paging->getLimit(6)),
            ]
        );
    }

    /**
     * @Route("/archive", methods={"GET"})
     *
     * @param Request           $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function archive(Request $request, ArticleRepository $articleRepository): Response
    {
        $paging = PagingRequest::create($request);
        $year   = $request->query->getInt('year');
        $years  = $articleRepository->findPublishingYears();
        if ($year > 0) {
            $articles  = $articleRepository->findPublishedInYearPaged($year, $paging->getPage(), $paging->getLimit());
            $yearIndex = array_search($year, $years, true);
            if ($yearIndex === false) {
                $years = array_slice($years, 0, 5);
            } else {
                $years = array_slice($years, max(0, $yearIndex - 2), 5);
            }
        } else {
            $articles = $articleRepository->findPublishedPaged($paging->getPage(), $paging->getLimit());
            $years    = array_slice($years, 0, 5);
        }

        return $this->render(
            'news/archive.html.twig',
            [
                'year'     => $year,
                'years'    => $years,
                'articles' => $articles,
            ]
        );
    }

    /**
     * @Route(
     *     "/{article}",
     *     methods={"GET"},
     *     requirements={"article": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     *
     * @param Article           $article
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function detail(Article $article, ArticleRepository $articleRepository): Response
    {
        if (!$article->isPublished()) {
            throw $this->createNotFoundException();
        }
        return $this->render(
            'news/detail.html.twig',
            [
                'article' => $article,
                'latest'  => $articleRepository->findMostRecent(2),
            ]
        );
    }

    public function legacyArticleContent(Article $article, ArticleRepository $articleRepository): Response
    {
        if (!$article->isPublished()) {
            throw $this->createNotFoundException();
        }
        $images    = $articleRepository->findImages($article);
        $documents = $articleRepository->findDocuments($article);
        return $this->render(
            'news/_legacy_article_content.html.twig',
            [
                'article'   => $article,
                'images'    => $images,
                'documents' => $documents,
            ]
        );
    }

    public function articlePrimaryImage(
        Request $request,
        Article $article,
        ArticleRepository $articleRepository
    ): Response {
        if (!$article->isPublished()) {
            throw $this->createNotFoundException();
        }
        $images = $articleRepository->findImages($article);
        return $this->render(
            'news/_article_primary_image.html.twig',
            [
                'article'            => $article,
                'legacyPrimaryImage' => $images->getPrimaryImage(),
                'class'              => $request->attributes->get('class'),
                'alt'                => $request->attributes->get('alt'),
                'defaultImage'       => $request->attributes->get('defaultImage'),
            ]
        );
    }
}
