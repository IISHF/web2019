<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use App\Application\Article\Command\ArticleWorkflowCommand;
use App\Application\Article\Command\CreateArticle;
use App\Application\Article\Command\DeleteArticle;
use App\Application\Article\Command\UpdateArticle;
use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Domain\Model\Article\ArticleVersionRepository;
use App\Infrastructure\Article\Form\CreateArticleType;
use App\Infrastructure\Article\Form\UpdateArticleType;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Controller\PagingRequest;
use App\Infrastructure\File\FileUploader;
use App\Infrastructure\Workflow\WorkflowMetadata;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Workflow\Registry as WorkflowRegistry;

/**
 * Class ArticleController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/articles")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ArticleController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param Request           $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function list(Request $request, ArticleRepository $articleRepository): Response
    {
        $paging  = PagingRequest::create($request);
        $showAll = $request->query->getBoolean('all');
        if (!$this->isGranted('ROLE_ADMIN')) {
            $showAll = false;
        }
        if ($showAll) {
            $articles = $articleRepository->findAllPaged($paging->getPage(), $paging->getLimit());
        } else {
            $articles = $articleRepository->findPublishedPaged($paging->getPage(), $paging->getLimit());
        }

        return $this->render(
            'admin/article/list.html.twig',
            [
                'showAll'  => $showAll,
                'articles' => $articles,
            ]
        );
    }

    /**
     * @Route("/upload", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request      $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function upload(Request $request, FileUploader $fileUploader): Response
    {
        $file = $fileUploader->uploadFile($request, Article::FILE_ORIGIN);

        return JsonResponse::create(
            [
                'filename'    => $file->getOriginalName(),
                'contentType' => $file->getMimeType(),
                'filesize'    => $file->getSize(),
                'url'         => $file->getPath(),
                'href'        => $file->getPath(),
            ]
        );
    }

    /**
     * @Route("/create", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            throw new BadRequestHttpException();
        }

        $create = CreateArticle::create($user->getUsername());
        $form   = $this->createForm(CreateArticleType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new article has been created.');
            return $this->redirectToRoute('app_admin_article_list', ['all' => true]);
        }

        return $this->render(
            'admin/article/create.html.twig',
            [
                'author' => $create->getAuthor(),
                'form'   => $form->createView(),
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
     * @param Article                  $article
     * @param ArticleRepository        $articleRepository
     * @param ArticleVersionRepository $versionRepository
     * @return Response
     */
    public function detail(
        Article $article,
        ArticleRepository $articleRepository,
        ArticleVersionRepository $versionRepository
    ): Response {
        $versions = [];
        if ($this->isGranted('ARTICLE_EDIT', $article)) {
            $versions = $versionRepository->getLogEntries($article);
        } elseif (!$article->isPublished()) {
            throw $this->createNotFoundException();
        }

        $images    = $articleRepository->findImages($article);
        $documents = $articleRepository->findDocuments($article);

        return $this->render(
            'admin/article/detail.html.twig',
            [
                'article'   => $article,
                'images'    => $images,
                'documents' => $documents,
                'versions'  => $versions,
            ]
        );
    }

    /**
     * @Route(
     *     "/{article}/preview/{version<\d+>}",
     *     methods={"GET"},
     *     requirements={"article": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     * @Security("is_granted('ARTICLE_EDIT', article)")
     *
     * @param Article                  $article
     * @param int                      $version
     * @param ArticleVersionRepository $versionRepository
     * @return Response
     */
    public function preview(Article $article, int $version, ArticleVersionRepository $versionRepository): Response
    {
        if ($article->isLegacyFormat()) {
            throw $this->createAccessDeniedException();
        }

        $articleSlug = $article->getSlug();
        $versionRepository->revert($article, $version);

        return $this->render(
            'admin/article/preview.html.twig',
            [
                'article'     => $article,
                'version'     => $version,
                'articleSlug' => $articleSlug,
            ]
        );
    }

    /**
     * @Route(
     *     "/{article}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"article": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     * @Security("is_granted('ARTICLE_EDIT', article)")
     *
     * @param Request             $request
     * @param Article             $article
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Article $article, MessageBusInterface $commandBus): Response
    {
        if ($article->isLegacyFormat()) {
            throw $this->createAccessDeniedException();
        }

        $update = UpdateArticle::update($article);
        $form   = $this->createForm(
            UpdateArticleType::class,
            $update,
            ['add_published_date' => $article->isPublished(),]
        );

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The article has been updated.');

            return $this->redirectToRoute('app_admin_article_list', ['all' => true]);
        }

        return $this->render(
            'admin/article/update.html.twig',
            [
                'article' => $article,
                'form'    => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{article}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"article": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     * @Security("is_granted('ARTICLE_DELETE', article)")
     *
     * @param Request             $request
     * @param Article             $article
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, Article $article, MessageBusInterface $commandBus): Response
    {
        if ($article->isLegacyFormat()) {
            throw $this->createAccessDeniedException();
        }

        $delete = DeleteArticle::delete($article);

        $this->handleCsrfCommand($delete, 'article_delete_' . $article->getId(), $request, $commandBus);

        $this->addFlash('success', 'The article has been deleted.');

        return $this->redirectToRoute('app_admin_article_list', ['all' => true]);
    }

    /**
     * @Route(
     *     "/{article}/workflow/{transition<\w+>}",
     *     methods={"GET", "POST"},
     *     requirements={"article": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
     * @Security("is_granted('ARTICLE_EDIT', article)")
     *
     * @param Request             $request
     * @param Article             $article
     * @param string              $transition
     * @param MessageBusInterface $commandBus
     * @param WorkflowRegistry    $workflowRegistry
     * @return Response
     */
    public function workflow(
        Request $request,
        Article $article,
        string $transition,
        MessageBusInterface $commandBus,
        WorkflowRegistry $workflowRegistry
    ): Response {

        $transitionMeta = WorkflowMetadata::find($workflowRegistry, $article, 'article_publishing')
                                          ->findEnabledTransition($transition);
        if (!$transitionMeta) {
            throw new BadRequestHttpException();
        }

        $successMessage = $transitionMeta->getMetadata('success_message', 'The article has been updated.');

        $command = ArticleWorkflowCommand::create($article, $transition);
        if (($formType = $transitionMeta->getFormType()) !== null) {
            $form = $this->createForm($formType, $command);
            if ($this->handleForm($command, $form, $request, $commandBus)) {
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('app_admin_article_list', ['all' => true]);
            }

            return $this->render(
                'admin/article/workflow.html.twig',
                [
                    'article'    => $article,
                    'transition' => $transitionMeta->getTransition(),
                    'form'       => $form->createView(),
                ]
            );
        }

        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        $this->handleCsrfCommand(
            $command,
            'article_' . $article->getId() . '_' . $transitionMeta->getName(),
            $request,
            $commandBus
        );

        $this->addFlash('success', $successMessage);

        return $this->redirectToRoute('app_admin_article_list', ['all' => true]);
    }
}
