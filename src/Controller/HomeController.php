<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("")
     *
     * @return Response
     */
    public function index(): Response
    {
        $number = random_int(0, 100);

        return $this->render('home/index.html.twig', ['number' => $number]);
    }
}
