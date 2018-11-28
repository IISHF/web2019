<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 17:38
 */

namespace App\Infrastructure\Security;

use App\Domain\Model\User\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class LoginSubscriber
 *
 * @package App\Infrastructure\Security
 */
class LoginSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param UserRepository $repository
     * @param RequestStack   $requestStack
     */
    public function __construct(UserRepository $repository, RequestStack $requestStack)
    {
        $this->repository   = $repository;
        $this->requestStack = $requestStack;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $token = $event->getAuthenticationToken();
        $user  = $this->repository->findByUsername($token->getUsername());

        if (!$user) {
            return;
        }
        $request   = $event->getRequest();
        $userIp    = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $user->registerLogin($userIp, $userAgent);
        $this->repository->save($user);
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return;
        }

        $token = $event->getAuthenticationToken();
        $user  = $this->repository->findByUsername($token->getUsername());
        if (!$user) {
            return;
        }
        $userIp    = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $user->registerLoginFailure($userIp, $userAgent);
        $this->repository->save($user);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN            => 'onInteractiveLogin',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }
}
