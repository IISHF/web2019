<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller\Admin
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/admin")
     *
     * @return Response
     */
    public function index(): Response
    {
        $config = [
            'homeUrl' => $this->generateUrl('app_home_index'),
            'baseUrl' => $this->generateUrl('app_admin_home_index'),
        ];

        return $this->render('admin/home/index.html.twig', ['config' => $config]);
    }
}
