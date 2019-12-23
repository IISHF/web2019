<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:06
 */

namespace App\Infrastructure\Security\User;

use App\Domain\Model\User\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Throwable;
use function get_class;

/**
 * Class UserProvider
 *
 * @package App\Infrastructure\Security\User
 */
class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->userRepository->findByEmail($username);
        if (!$user) {
            $ex = new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            $ex->setUsername($username);
            throw $ex;
        }

        return new User(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $user->isConfirmed(),
            $user->getRoles()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return $class === User::class;
    }

    /**
     * {@inheritdoc}
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            return;
        }
        $appUser = $this->userRepository->findById($user->getId());
        if (!$appUser) {
            return;
        }

        try {
            $appUser->changePassword($newEncodedPassword);
            $this->userRepository->save($appUser);
        } catch (Throwable $e) {
            return;
        }
    }
}
