<?php

namespace App\Controller;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('news/index.html.twig');
    }

    /**
     * @Route("/archive", methods={"GET"})
     *
     * @return Response
     */
    public function archive(): Response
    {
        return $this->render('news/archive.html.twig');
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
        $images    = $articleRepository->findImages($article);
        $documents = $articleRepository->findDocuments($article);
        return $this->render(
            'news/detail.html.twig',
            [
                'article'   => $article,
                'images'    => $images,
                'documents' => $documents,
            ]
        );
    }
}
