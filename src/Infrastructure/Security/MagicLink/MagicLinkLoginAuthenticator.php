<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 13:23
 */

namespace App\Infrastructure\Security\MagicLink;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class MagicLinkLoginAuthenticator
 *
 * @package App\Infrastructure\Security\MagicLink
 */
class MagicLinkLoginAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var HttpUtils
     */
    private $httpUtils;

    /**
     * @param TokenManager          $tokenManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param HttpUtils             $httpUtils
     */
    public function __construct(TokenManager $tokenManager, UrlGeneratorInterface $urlGenerator, HttpUtils $httpUtils)
    {
        $this->tokenManager = $tokenManager;
        $this->urlGenerator = $urlGenerator;
        $this->httpUtils    = $httpUtils;
    }

    /**
     * @param string|null $redirectTo
     * @return string
     */
    protected function getLoginUrl($redirectTo = null): string
    {
        return $this->urlGenerator->generate(
            'login',
            [
                'tab'         => 'magic',
                'redirect_to' => $redirectTo,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('GET')
            && $this->httpUtils->checkRequestPath($request, 'login_magic_link');
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->getLoginUrl($request->getPathInfo()));
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $username = trim($request->query->get('email', ''));
        if (strlen($username) > Security::MAX_USERNAME_LENGTH) {
            throw new BadCredentialsException('Invalid username.');
        }

        if ($session = $request->getSession()) {
            $session->set(Security::LAST_USERNAME, $username);
        }

        return [
            'username'    => $username,
            'token'       => trim($request->query->getAlnum('token', '')),
            'signature'   => trim($request->query->getAlnum('sig', '')),
            'redirect_to' => $request->query->get('redirect_to'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $username = $credentials['username'];
        try {
            $user = $userProvider->loadUserByUsername($username);

            if (!$user instanceof UserInterface) {
                throw new AuthenticationServiceException('The user provider must return a UserInterface object.');
            }

            return $user;
        } catch (UsernameNotFoundException $notFound) {
            throw new BadCredentialsException('Bad credentials.', 0, $notFound);
        } catch (\Exception $repositoryProblem) {
            throw new AuthenticationServiceException($repositoryProblem->getMessage(), 0, $repositoryProblem);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (strtolower($credentials['username']) !== strtolower($user->getUsername())) {
            throw new BadCredentialsException('The username did not match.');
        }

        $this->tokenManager->assertTokenValid(
            $credentials['token'],
            $credentials['username'],
            $credentials['signature'],
            $credentials['redirect_to']
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($session = $request->getSession()) {
            $session->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->getLoginUrl($request->get('redirect_to')));
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // check URL or request parameter first
        $targetPath = $request->get('redirect_to');

        // fallback to default
        if (!$targetPath) {
            $targetPath = 'home';
        }

        return $this->httpUtils->createRedirectResponse($request, $targetPath);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}
