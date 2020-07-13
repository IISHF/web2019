<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaController
 *
 * @package App\Controller
 *
 * @Route("/media")
 */
class MediaController extends AbstractController
{
    /**
     * @Route("/photo", methods={"GET"})
     *
     * @return Response
     */
    public function photo(): Response
    {
        return $this->render('media/photo.html.twig');
    }

    /**
     * @Route("/video", methods={"GET"})
     *
     * @return Response
     */
    public function video(): Response
    {
        return $this->render('media/video.html.twig');
    }
}
