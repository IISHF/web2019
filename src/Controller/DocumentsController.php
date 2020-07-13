<?php

namespace App\Controller;

use App\Domain\Model\Document\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentsController
 *
 * @package App\Controller
 *
 * @Route("/documents")
 */
class DocumentsController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render(
            'documents/index.html.twig',
            [
                'documents' => $documentRepository->findCurrentlyValid(),
            ]
        );
    }
}
