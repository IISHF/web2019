<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommitteesController
 *
 * @package App\Controller
 *
 * @Route("/iishf/committees")
 */
class CommitteesController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('committees/index.html.twig');
    }
}
