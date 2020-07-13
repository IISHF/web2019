<?php

namespace App\Controller;

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
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('documents/index.html.twig');
    }
}
