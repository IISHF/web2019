<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller;

use App\Application\User\Command\ChangePassword;
use App\Application\User\Command\ConfirmUser;
use App\Application\User\Command\RequestPasswordReset;
use App\Application\User\Command\ResetPassword;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\User\Form\ChangePasswordType;
use App\Infrastructure\User\Form\ConfirmUserType;
use App\Infrastructure\User\Form\RequestPasswordResetType;
use App\Infrastructure\User\Form\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AccountController
 *
 * @package App\Controller
 *
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    use FormHandler;

    /**
     * @Route("/change-password", methods={"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function changePassword(Request $request, MessageBusInterface $commandBus): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $changePassword = ChangePassword::change($user, $request);
        $form           = $this->createForm(ChangePasswordType::class, $changePassword);

        if ($this->handleForm($changePassword, $form, $request, $commandBus)) {
            $this->addFlash('success', 'Your password has been changed.');
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'account/change_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/forgot-password", methods={"GET", "POST"})
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function forgotPassword(Request $request, MessageBusInterface $commandBus): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $requestPassword = RequestPasswordReset::request($request);
        $form            = $this->createForm(RequestPasswordResetType::class, $requestPassword);

        if ($this->handleForm($requestPassword, $form, $request, $commandBus)) {
            $this->addFlash(
                'success',
                'Your password has been reset. Please check your email for further instructions.'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'account/forgot_password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/reset-password", methods={"GET", "POST"})
     *
     * @param Request             $request
     * @param UserRepository      $userRepository
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        MessageBusInterface $commandBus
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $resetPasswordToken = $request->query->get('token');
        if (!$resetPasswordToken) {
            throw new BadRequestHttpException();
        }
        $user = $userRepository->findByResetPasswordToken($resetPasswordToken);
        if (!$user) {
            throw new BadRequestHttpException();
        }

        $resetPassword = ResetPassword::reset($resetPasswordToken, $request);
        $form          = $this->createForm(ResetPasswordType::class, $resetPassword);

        if ($this->handleForm($resetPassword, $form, $request, $commandBus)) {
            $this->addFlash('success', 'Your password has been reset. Please login.');

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'account/reset_password.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/confirm-user", methods={"GET", "POST"})
     *
     * @param Request             $request
     * @param UserRepository      $userRepository
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function confirmUser(
        Request $request,
        UserRepository $userRepository,
        MessageBusInterface $commandBus
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $confirmToken = $request->query->get('token');
        if (!$confirmToken) {
            throw new BadRequestHttpException();
        }
        $user = $userRepository->findByConfirmToken($confirmToken);
        if (!$user) {
            throw new BadRequestHttpException();
        }

        $confirmUser = ConfirmUser::confirm($confirmToken, $request);
        $form        = $this->createForm(ConfirmUserType::class, $confirmUser);

        if ($this->handleForm($confirmUser, $form, $request, $commandBus)) {
            $this->addFlash('success', 'Your user account has been confirmed. Please login.');

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'account/confirm_user.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
