<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'admin/home/index.html.twig',
            [
            ]
        );
    }
}
