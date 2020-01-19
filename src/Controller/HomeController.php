<?php

namespace App\Controller;

use App\Domain\Model\Article\ArticleRepository;
use App\Infrastructure\Security\Exception\CaptchaTestFailedException;
use App\Infrastructure\Security\MagicLink\TokenManager;
use App\Infrastructure\Security\ReCaptchaClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
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
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render(
            'home/index.html.twig',
            [
                'articles' => $articleRepository->findMostRecent(),
            ]
        );
    }

    /**
     * @return Response
     */
    public function aboutSport(): Response
    {
        return $this->render('home/about_sport.html.twig');
    }

    /**
     * @return Response
     */
    public function calendar(): Response
    {
        return $this->render('home/calendar.html.twig');
    }

    /**
     * @return Response
     */
    public function cookie(): Response
    {
        return $this->render('home/cookie.html.twig');
    }

    /**
     * @return Response
     */
    public function obligation(): Response
    {
        return $this->render('home/obligation.html.twig');
    }

    /**
     * @return Response
     */
    public function photo(): Response
    {
        return $this->render('home/photo.html.twig');
    }

    /**
     * @return Response
     */
    public function privacy(): Response
    {
        return $this->render('home/privacy.html.twig');
    }

    /**
     * @return Response
     */
    public function video(): Response
    {
        return $this->render('home/video.html.twig');
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
        $captchaError = $error instanceof CaptchaTestFailedException;
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'home/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
                'captchaError'  => $captchaError,
                'redirect_to'   => $request->get('redirect_to', null),
                'activeTab'     => $request->query->get('tab', 'password') === 'magic' ? 'magic' : 'password',
            ]
        );
    }

    /**
     * @param Request          $request
     * @param SessionInterface $session
     * @param TokenManager     $tokenManager
     * @param ReCaptchaClient  $reCaptchaClient
     * @return Response
     */
    public function loginMagic(
        Request $request,
        SessionInterface $session,
        TokenManager $tokenManager,
        ReCaptchaClient $reCaptchaClient
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        $username   = trim($request->request->get('email'));
        $redirectTo = $request->get('redirect_to', null);
        if (!$request->hasPreviousSession()) {
            return $this->redirectToRoute('home');
        }

        if (!$this->isCsrfTokenValid('login_magic', $request->request->get('csrf_token'))) {
            return $this->handleMagicLinkLoginError(
                $username,
                $redirectTo,
                $session,
                new InvalidCsrfTokenException()
            );
        }

        if (!$reCaptchaClient->validateRequest($request)) {
            return $this->handleMagicLinkLoginError(
                $username,
                $redirectTo,
                $session,
                new CaptchaTestFailedException()
            );
        }

        $session->set(Security::LAST_USERNAME, '');
        try {
            $tokenManager->createToken(
                $username,
                $request->getClientIp(),
                $request->headers->get('User-Agent'),
                $redirectTo
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
     * @param string                  $username
     * @param string|null             $redirectTo
     * @param SessionInterface        $session
     * @param AuthenticationException $error
     * @return Response
     */
    private function handleMagicLinkLoginError(
        string $username,
        ?string $redirectTo,
        SessionInterface $session,
        AuthenticationException $error
    ): Response {
        $session->set(Security::AUTHENTICATION_ERROR, $error);
        $session->set(Security::LAST_USERNAME, $username);

        return $this->redirectToRoute(
            'login',
            [
                'redirect_to' => $redirectTo,
                'tab'         => 'magic',
            ]
        );
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
