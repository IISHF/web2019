<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:53
 */

namespace App\Controller;

use App\Application\Document\Command\CreateDocument;
use App\Application\Document\Command\CreateDocumentVersion;
use App\Application\Document\Command\DeleteDocument;
use App\Application\Document\Command\DeleteDocumentVersion;
use App\Application\Document\Command\UpdateDocument;
use App\Application\Document\Command\UpdateDocumentVersion;
use App\Domain\Model\Document\Document;
use App\Domain\Model\Document\DocumentRepository;
use App\Domain\Model\Document\DocumentVersion;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Controller\PagingRequest;
use App\Infrastructure\Document\Form\CreateDocumentType;
use App\Infrastructure\Document\Form\CreateDocumentVersionType;
use App\Infrastructure\Document\Form\UpdateDocumentType;
use App\Infrastructure\Document\Form\UpdateDocumentVersionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 *
 * @package App\Controller
 *
 * @Route("/documents")
 */
class DocumentController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param Request            $request
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function list(Request $request, DocumentRepository $documentRepository): Response
    {
        $paging = PagingRequest::create($request);
        return $this->render(
            'document/list.html.twig',
            [
                'documents' => $documentRepository->findPaged($paging->getPage(), $paging->getLimit()),
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
        $create = CreateDocument::create();
        $form   = $this->createForm(CreateDocumentType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new document has been created.');

            return $this->redirectToRoute('app_document_list');
        }

        return $this->render(
            'document/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{document}",
     *     methods={"GET"},
     *     requirements={"document": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="document",
     *      class="App\Domain\Model\Document\Document",
     *      converter="app.document",
     *      options={"with_versions": true}
     * )
     *
     * @param Document $document
     * @return Response
     */
    public function detail(Document $document): Response
    {
        return $this->render(
            'document/detail.html.twig',
            [
                'document' => $document,
            ]
        );
    }

    /**
     * @Route(
     *     "/{document}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"document": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="document",
     *      class="App\Domain\Model\Document\Document",
     *      converter="app.document"
     * )
     * @Security("is_granted('DOCUMENT_EDIT', document)")
     *
     * @param Request             $request
     * @param Document            $document
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Document $document, MessageBusInterface $commandBus): Response
    {
        $update = UpdateDocument::update($document);
        $form   = $this->createForm(UpdateDocumentType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The document has been updated.');

            return $this->redirectToRoute('app_document_list');
        }

        return $this->render(
            'document/update.html.twig',
            [
                'document' => $document,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{document}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"document": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="document",
     *      class="App\Domain\Model\Document\Document",
     *      converter="app.document"
     * )
     * @Security("is_granted('DOCUMENT_DELETE', document)")
     *
     * @param Request             $request
     * @param Document            $document
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, Document $document, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteDocument::delete($document);

        $this->handleCsrfCommand($delete, 'document_delete_' . $document->getId(), $request, $commandBus);

        $this->addFlash('success', 'The document has been deleted.');

        return $this->redirectToRoute('app_document_list');

    }

    /**
     * @Route(
     *     "/{document}/create",
     *     methods={"GET", "POST"},
     *     requirements={"document": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="document",
     *      class="App\Domain\Model\Document\Document",
     *      converter="app.document"
     * )
     * @Security("is_granted('DOCUMENT_EDIT', document)")
     *
     * @param Request             $request
     * @param Document            $document
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createVersion(Request $request, Document $document, MessageBusInterface $commandBus): Response
    {
        $create = CreateDocumentVersion::create($document->getId());
        $form   = $this->createForm(CreateDocumentVersionType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new document version has been created.');

            return $this->redirectToRoute('app_document_detail', ['document' => $document->getSlug()]);
        }

        return $this->render(
            'document/version_create.html.twig',
            [
                'document' => $document,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{document}/{version}",
     *      methods={"GET"},
     *      requirements={
     *          "document": "%routing.slug%",
     *          "version": "%routing.slug%"
     *      }
     * )
     * @ParamConverter(
     *      name="version",
     *      class="App\Domain\Model\Document\DocumentVersion",
     *      converter="app.document_version"
     * )
     *
     * @param DocumentVersion $version
     * @return Response
     */
    public function version(DocumentVersion $version): Response
    {
        return $this->render(
            'document/version_detail.html.twig',
            [
                'version' => $version,
            ]
        );
    }

    /**
     * @Route(
     *     "/{document}/{version}/edit",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "document": "%routing.uuid%",
     *          "version": "%routing.uuid%"
     *      }
     * )
     * @ParamConverter(
     *      name="version",
     *      class="App\Domain\Model\Document\DocumentVersion",
     *      converter="app.document_version"
     * )
     * @Security("is_granted('DOCUMENT_VERSION_EDIT', version)")
     *
     * @param Request             $request
     * @param DocumentVersion     $version
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function updateVersion(Request $request, DocumentVersion $version, MessageBusInterface $commandBus): Response
    {
        $update = UpdateDocumentVersion::update($version);
        $form   = $this->createForm(UpdateDocumentVersionType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The document version has been updated.');

            return $this->redirectToRoute('app_document_detail', ['document' => $version->getDocument()->getSlug()]);
        }

        return $this->render(
            'document/version_update.html.twig',
            [
                'version' => $version,
                'form'    => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{document}/{version}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={
     *          "document": "%routing.uuid%",
     *          "version": "%routing.uuid%"
     *      }
     * )
     * @ParamConverter(
     *      name="version",
     *      class="App\Domain\Model\Document\DocumentVersion",
     *      converter="app.document_version"
     * )
     * @Security("is_granted('DOCUMENT_VERSION_DELETE', version)")
     *
     * @param Request             $request
     * @param DocumentVersion     $version
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function deleteVersion(Request $request, DocumentVersion $version, MessageBusInterface $commandBus): Response
    {
        $document = $version->getDocument();
        $delete   = DeleteDocumentVersion::delete($version);

        $this->handleCsrfCommand($delete, 'document_version_delete_' . $version->getId(), $request, $commandBus);

        $this->addFlash('success', 'The document version has been deleted.');

        return $this->redirectToRoute('app_document_detail', ['document' => $document->getSlug()]);
    }
}
