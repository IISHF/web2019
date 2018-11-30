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
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UserCommandHandler
 *
 * @package App\Application\User\Command
 */
abstract class UserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $id
     * @return User
     */
    protected function getUser(string $id): User
    {
        $user = $this->repository->findById($id);
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
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            throw new \OutOfBoundsException('No user found for email ' . $email);
        }
        return $user;
    }
}
