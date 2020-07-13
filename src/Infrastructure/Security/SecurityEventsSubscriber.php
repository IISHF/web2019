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
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Class SecurityEventsSubscriber
 *
 * @package App\Infrastructure\Security
 */
class SecurityEventsSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param UserRepository $userRepository
     * @param RequestStack   $requestStack
     */
    public function __construct(UserRepository $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack   = $requestStack;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $token = $event->getAuthenticationToken();
        $user  = $this->userRepository->findByEmail($token->getUsername());

        if (!$user) {
            return;
        }
        $request   = $event->getRequest();
        $userIp    = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $user->registerLogin($userIp, $userAgent);
        $this->userRepository->save($user);
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
        $user  = $this->userRepository->findByEmail($token->getUsername());
        if (!$user) {
            return;
        }
        $userIp    = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $user->registerLoginFailure($userIp, $userAgent);
        $this->userRepository->save($user);
    }

    /**
     * @param LogoutEvent $event
     */
    public function onLogout(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if ($token && ($user = $this->userRepository->findByEmail($token->getUsername())) !== null) {
            $user->registerLogout();
            $this->userRepository->save($user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class      => 'onInteractiveLogin',
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
            LogoutEvent::class                => 'onLogout',
        ];
    }
}
