<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembersController
 *
 * @package App\Controller
 *
 * @Route("/members")
 */
class MembersController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('members/index.twig');
    }
}
