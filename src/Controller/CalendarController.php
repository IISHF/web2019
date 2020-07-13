<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CalendarController
 *
 * @package App\Controller
 *
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig');
    }
}
