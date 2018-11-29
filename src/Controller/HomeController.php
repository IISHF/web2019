<?php

namespace App\Controller;

use App\Application\User\Command\ChangePassword;
use App\Infrastructure\User\Form\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
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

    /**
     * @Security("is_fully_authenticated()")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function changePassword(Request $request, MessageBusInterface $commandBus): Response
    {
        $user = $this->getUser();

        $changePassword = ChangePassword::change($user, $request);
        $form           = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($changePassword);
            $this->addFlash(
                'success',
                'Your password has been changed.'
            );
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'home/change_password.html.twig',
            [
                'user'       => $user,
                'changeForm' => $form->createView(),
            ]
        );
    }
}
