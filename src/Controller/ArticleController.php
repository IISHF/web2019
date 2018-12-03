<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 17:41
 */

namespace App\Controller;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 *
 * @package App\Controller
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", methods={"GET"})
     *
     * @param Request           $request
     * @param ArticleRepository $repository
     * @return Response
     */
    public function getList(Request $request, ArticleRepository $repository): Response
    {
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 30);

        return $this->render(
            'article/list.html.twig',
            [
                'articles' => $repository->findPaged($page, $limit),
            ]
        );
    }

    /**
     * @Route("/articles/{article<[0-9a-z-]+>}", methods={"GET"})
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     *
     * @param Article $article
     * @return Response
     */
    public function getDetail(Article $article): Response
    {
        return $this->render(
            'article/detail.html.twig',
            [
                'article' => $article,
            ]
        );
    }
}
