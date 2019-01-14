<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 17:30
 */

namespace App\Infrastructure\Security;

use App\Domain\Model\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * Class LogoutSuccessHandler
 *
 * @package App\Infrastructure\Security
 */
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var LogoutSuccessHandlerInterface
     */
    private $inner;

    /**
     * @param UserRepository                $userRepository
     * @param TokenStorageInterface         $tokenStorage
     * @param LogoutSuccessHandlerInterface $inner
     */
    public function __construct(
        UserRepository $userRepository,
        TokenStorageInterface $tokenStorage,
        LogoutSuccessHandlerInterface $inner
    ) {
        $this->userRepository = $userRepository;
        $this->tokenStorage   = $tokenStorage;
        $this->inner          = $inner;
    }

    /**
     * {@inheritdoc}
     */
    public function onLogoutSuccess(Request $request): Response
    {
        $token = $this->tokenStorage->getToken();
        if ($token && ($user = $this->userRepository->findByEmail($token->getUsername())) !== null) {
            $user->registerLogout();
            $this->userRepository->save($user);
        }
        return $this->inner->onLogoutSuccess($request);
    }
}
