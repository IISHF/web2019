<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 17:41
 */

namespace App\Controller;

use App\Application\Article\Command\ArticleWorkflowCommand;
use App\Application\Article\Command\CreateArticle;
use App\Application\Article\Command\DeleteArticle;
use App\Application\Article\Command\UpdateArticle;
use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Domain\Model\Article\ArticleVersionRepository;
use App\Infrastructure\Article\Form\CreateArticleType;
use App\Infrastructure\Article\Form\UpdateArticleType;
use App\Infrastructure\File\FileUploader;
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
use Symfony\Component\Workflow\Transition as WorkflowTransition;
use Symfony\Component\Workflow\Workflow;

/**
 * Class ArticleController
 *
 * @package App\Controller
 *
 * @Route("/articles")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request           $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function list(Request $request, ArticleRepository $articleRepository): Response
    {
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 30);

        $showAll = $request->query->getBoolean('all');
        if (!$this->isGranted('ROLE_ADMIN')) {
            $showAll = false;
        }
        if ($showAll) {
            $articles = $articleRepository->findAllPaged($page, $limit);
        } else {
            $articles = $articleRepository->findPublishedPaged($page, $limit);
        }

        return $this->render(
            'article/list.html.twig',
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

        $createArticle = CreateArticle::create($user->getUsername());
        $form          = $this->createForm(CreateArticleType::class, $createArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($createArticle);
            $this->addFlash('success', 'The new article has been created.');

            return $this->redirectToRoute('app_article_list', ['all' => true]);
        }

        return $this->render(
            'article/create.html.twig',
            [
                'author' => $createArticle->getAuthor(),
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
            'article/detail.html.twig',
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
     * @Security("is_granted('ARTICLE_EDIT', article)")
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
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
            'article/preview.html.twig',
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
        if ($article->isLegacyFormat()) {
            throw $this->createAccessDeniedException();
        }

        $updateArticle = UpdateArticle::update($article);
        $form          = $this->createForm(
            UpdateArticleType::class,
            $updateArticle,
            [
                'add_published_date' => $article->isPublished(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($updateArticle);
            $this->addFlash('success', 'The article has been updated.');

            return $this->redirectToRoute('app_article_list', ['all' => true]);
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
     * @Route(
     *     "/{article}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"article": "%routing.uuid%"}
     * )
     * @Security("is_granted('ARTICLE_DELETE', article)")
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
    public function delete(Request $request, Article $article, MessageBusInterface $commandBus): Response
    {
        if ($article->isLegacyFormat()) {
            throw $this->createAccessDeniedException();
        }

        $deleteArticle = DeleteArticle::delete($article);

        if (!$this->isCsrfTokenValid(
            'article_delete_' . $article->getId(),
            $request->request->get('_token')
        )) {
            throw new BadRequestHttpException();
        }

        $commandBus->dispatch($deleteArticle);
        $this->addFlash('success', 'The article has been deleted.');

        return $this->redirectToRoute('app_article_list', ['all' => true]);
    }

    /**
     * @Route(
     *     "/{article}/workflow/{transition<\w+>}",
     *     methods={"GET", "POST"},
     *     requirements={"article": "%routing.uuid%"}
     * )
     * @Security("is_granted('ARTICLE_EDIT', article)")
     * @ParamConverter(
     *      name="article",
     *      class="App\Domain\Model\Article\Article",
     *      converter="app.article"
     * )
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
        $workflow           = $workflowRegistry->get($article, 'article_publishing');
        $transitionInstance = $this->findTransition($workflow, $article, $transition);
        $metaDataStore      = $workflow->getMetadataStore();

        $successMessage = $metaDataStore->getMetadata('success_message', $transitionInstance);
        if (!$successMessage) {
            $successMessage = 'The article has been updated.';
        }

        $command = ArticleWorkflowCommand::create($article, $transition);
        if (($formType = $metaDataStore->getMetadata('form_type', $transitionInstance)) !== null) {
            return $this->handleFormTransition(
                $request,
                $article,
                $transitionInstance,
                $formType,
                $command,
                $successMessage,
                $commandBus
            );
        }

        return $this->handleTransition($request, $article, $transitionInstance, $command, $successMessage, $commandBus);
    }

    /**
     * @param Workflow $workflow
     * @param Article  $article
     * @param string   $transition
     * @return WorkflowTransition
     */
    private function findTransition(Workflow $workflow, Article $article, string $transition): WorkflowTransition
    {
        $transitionInstance = null;
        foreach ($workflow->getEnabledTransitions($article) as $t) {
            /** @var WorkflowTransition $t */
            if ($t->getName() === $transition) {
                $transitionInstance = $t;
                break;
            }
        }
        if (!$transitionInstance) {
            throw new BadRequestHttpException();
        }
        return $transitionInstance;
    }

    /**
     * @param Request                $request
     * @param Article                $article
     * @param WorkflowTransition     $transition
     * @param string                 $formType
     * @param ArticleWorkflowCommand $command
     * @param string                 $successMessage
     * @param MessageBusInterface    $commandBus
     * @return Response
     */
    private function handleFormTransition(
        Request $request,
        Article $article,
        WorkflowTransition $transition,
        string $formType,
        ArticleWorkflowCommand $command,
        string $successMessage,
        MessageBusInterface $commandBus
    ): Response {
        $form = $this->createForm($formType, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($command);
            $this->addFlash('success', $successMessage);

            return $this->redirectToRoute('app_article_list', ['all' => true]);
        }

        return $this->render(
            'article/workflow.html.twig',
            [
                'article'    => $article,
                'transition' => $transition,
                'form'       => $form->createView(),
            ]
        );
    }

    /**
     * @param Request                $request
     * @param Article                $article
     * @param WorkflowTransition     $transition
     * @param ArticleWorkflowCommand $command
     * @param string                 $successMessage
     * @param MessageBusInterface    $commandBus
     * @return Response
     */
    private function handleTransition(
        Request $request,
        Article $article,
        WorkflowTransition $transition,
        ArticleWorkflowCommand $command,
        string $successMessage,
        MessageBusInterface $commandBus
    ): Response {
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }
        if (!$this->isCsrfTokenValid(
            'article_' . $transition->getName() . '_' . $article->getId(),
            $request->request->get('_token')
        )) {
            throw new BadRequestHttpException();
        }

        $commandBus->dispatch($command);
        $this->addFlash('success', $successMessage);

        return $this->redirectToRoute('app_article_list', ['all' => true]);
    }
}
