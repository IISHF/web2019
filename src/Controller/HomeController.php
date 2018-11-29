<?php

namespace App\Controller;

use App\Application\User\Command\ChangePassword;
use App\Application\User\Command\RequestPasswordReset;
use App\Application\User\Command\ResetPassword;
use App\Infrastructure\User\Form\ChangePasswordType;
use App\Infrastructure\User\Form\RequestPasswordResetType;
use App\Infrastructure\User\Form\ResetPasswordType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

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
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function changePassword(Request $request, MessageBusInterface $commandBus): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

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
                'user'               => $user,
                'changePasswordForm' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @param LoggerInterface     $logger
     * @return Response
     */
    public function forgotPassword(Request $request, MessageBusInterface $commandBus, LoggerInterface $logger): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $requestPassword = RequestPasswordReset::request($request);
        $form            = $this->createForm(RequestPasswordResetType::class, $requestPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($requestPassword);
            $this->addFlash(
                'success',
                'Your password has been reset. Please check your email for further instructions.'
            );

            $resetUrl = $this->generateUrl(
                'reset_password',
                ['token' => $requestPassword->getResetPasswordToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $logger->info('Reset URL: ' . $resetUrl);

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'home/forgot_password.html.twig',
            [
                'forgotPasswordForm' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function resetPassword(Request $request, MessageBusInterface $commandBus): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $resetPasswordToken = $request->query->get('token');
        if (!$resetPasswordToken) {
            throw new BadRequestHttpException();
        }

        $resetPassword = ResetPassword::reset($resetPasswordToken, $request);
        $form          = $this->createForm(ResetPasswordType::class, $resetPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($resetPassword);
            $this->addFlash(
                'success',
                'Your password has been reset. Please login.'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'home/reset_password.html.twig',
            [
                'resetPasswordForm' => $form->createView(),
            ]
        );
    }
}
