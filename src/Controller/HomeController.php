<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    /**
     * @param Request             $request
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'home/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
                'redirect_to'   => $request->get('redirect_to', null),
            ]
        );
    }
}
