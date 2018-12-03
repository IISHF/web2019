<?php

namespace App\Controller;

use App\Infrastructure\Security\MagicLink\TokenManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
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
                'activeTab'     => $request->query->get('tab', 'password') === 'magic' ? 'magic' : 'password',
            ]
        );
    }

    /**
     * @param Request          $request
     * @param SessionInterface $session
     * @param TokenManager     $tokenManager
     * @return Response
     */
    public function loginMagic(Request $request, SessionInterface $session, TokenManager $tokenManager): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $username   = trim($request->request->get('email'));
        $redirectTo = $request->get('redirect_to', null);
        if (!$request->hasPreviousSession()) {
            return $this->redirectToRoute('home');
        }

        if (!$this->isCsrfTokenValid('login_magic', $request->request->get('csrf_token'))) {
            $session->set(Security::AUTHENTICATION_ERROR, new InvalidCsrfTokenException());
            $session->set(Security::LAST_USERNAME, $username);

            return $this->redirectToRoute(
                'login',
                [
                    'redirect_to' => $redirectTo,
                    'tab'         => 'clubs',
                ]
            );
        }

        $session->set(Security::LAST_USERNAME, '');
        try {
            $tokenManager->createToken(
                $username,
                $request->getClientIp(),
                $request->headers->get('User-Agent'),
                $redirectTo,
                new \DateTimeImmutable('now')
            );
        } catch (UsernameNotFoundException $e) {
            // user not found
            usleep(random_int(4500, 7500)); // prevent timing attacks
        } finally {
            $session->set('app.login_magic_processed', true);

        }
        return $this->redirectToRoute('login_magic_confirm');
    }

    /**
     * @param SessionInterface $session
     * @return Response
     */
    public function loginMagicConfirm(SessionInterface $session): Response
    {
        $magicLoginProcessed = $session->get('app.login_magic_processed', false);
        $session->remove('app.login_magic_processed');
        if (!$magicLoginProcessed || $this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        return $this->render('home/login_magic_confirm.html.twig');
    }
}
