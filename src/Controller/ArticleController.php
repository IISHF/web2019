<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 17:41
 */

namespace App\Controller;

use App\Application\Article\Command\CreateArticle;
use App\Application\Article\Command\UpdateArticle;
use App\Application\Article\Command\WorkflowCommand;
use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Infrastructure\Article\Form\CreateArticleType;
use App\Infrastructure\Article\Form\UpdateArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
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
                'articles' => $repository->findPaged(Article::STATE_ALL, $page, $limit),
            ]
        );
    }

    /**
     * @Route("/articles/create", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        $createArticle = CreateArticle::create();
        $form          = $this->createForm(CreateArticleType::class, $createArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($createArticle);
            $this->addFlash(
                'success',
                'The new article has been created.'
            );

            return $this->redirectToRoute('app_article_getlist');
        }

        return $this->render(
            'article/create.html.twig',
            [
                'form' => $form->createView(),
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

    /**
     * @Route("/articles/{article<[0-9a-z-]+>}/edit", methods={"GET", "POST"})
     * @Security("is_granted('ARTICLE_EDIT', article)")
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     *
     * @param Request             $request
     * @param Article             $article
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Article $article, MessageBusInterface $commandBus): Response
    {
        $updateArticle = UpdateArticle::update($article);
        $form          = $this->createForm(UpdateArticleType::class, $updateArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($updateArticle);
            $this->addFlash(
                'success',
                'The article has been updated.'
            );

            return $this->redirectToRoute('app_article_getlist');
        }

        return $this->render(
            'article/update.html.twig',
            [
                'article' => $article,
                'form'    => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/articles/{article<[0-9a-z-]+>}/workflow", methods={"GET", "POST"})
     * @Security("is_granted('ARTICLE_EDIT', article)")
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     *
     * @param Request             $request
     * @param Article             $article
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function workflow(Request $request, Article $article, MessageBusInterface $commandBus): Response
    {
        $transition = $request->request->get('transition');
        $command    = WorkflowCommand::transition($article, $transition);

        if (!$this->isCsrfTokenValid(
            'article_' . $transition . '_' . $article->getId(),
            $request->request->get('_token')
        )) {
            throw new BadRequestHttpException();
        }

        $commandBus->dispatch($command);
        $this->addFlash(
            'success',
            'The article has been updated.'
        );

        return $this->redirectToRoute('app_article_getlist');
    }
}
