<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 16:39
 */

namespace App\Controller\Admin;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ArticleController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"})
     *
     * @param Request             $request
     * @param ArticleRepository   $articleRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function list(
        Request $request,
        ArticleRepository $articleRepository,
        SerializerInterface $serializer
    ): Response {
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 30);

        $articles = $articleRepository->findPaged(Article::STATE_ALL, $page, $limit);

        return JsonResponse::fromJsonString(
            $serializer->serialize(
                [
                    'count' => $articles->getNbResults(),
                    'rows'  => array_map(
                        function (Article $article) {
                            return [
                                'id'           => $article->getId(),
                                'title'        => $article->getTitle(),
                                'subtitle'     => $article->getSubtitle(),
                                'state'        => $article->getCurrentState(),
                                'tags'         => $article->getTags(),
                                'published_at' => $article->getPublishedAt(),
                            ];
                        },
                        iterator_to_array($articles)
                    ),
                ],
                'json'
            )
        );
    }
}
