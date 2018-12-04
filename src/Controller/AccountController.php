<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 17:03
 */

namespace App\Controller;

use App\Application\User\Command\ChangePassword;
use App\Application\User\Command\ConfirmUser;
use App\Application\User\Command\RequestPasswordReset;
use App\Application\User\Command\ResetPassword;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\User\Form\ChangePasswordType;
use App\Infrastructure\User\Form\ConfirmUserType;
use App\Infrastructure\User\Form\RequestPasswordResetType;
use App\Infrastructure\User\Form\ResetPasswordType;
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
    /**
     * @Route("/change-password", methods={"GET", "POST"})
     *
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($requestPassword);
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($confirmUser);
            $this->addFlash(
                'success',
                'Your user account has been confirmed. Please login.'
            );

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
