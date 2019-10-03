<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:26
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;

/**
 * Class UserCommandHandler
 *
 * @package App\Application\User\Command
 */
abstract class UserCommandHandler
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $id
     * @return User
     */
    protected function getUser(string $id): User
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new \OutOfBoundsException('No user found for id ' . $id);
        }
        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    protected function getUserByEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new \OutOfBoundsException('No user found for email ' . $email);
        }
        return $user;
    }
}
