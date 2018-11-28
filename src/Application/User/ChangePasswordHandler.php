<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User;

use App\Domain\Model\User\UserRepository;

/**
 * Class ChangePasswordHandler
 *
 * @package App\Application\User
 */
class ChangePasswordHandler extends UserCommandHandler
{
    /**
     * @var PasswordService
     */
    private $passwordService;

    /**
     * @param UserRepository  $repository
     * @param PasswordService $passwordService
     */
    public function __construct(UserRepository $repository, PasswordService $passwordService)
    {
        parent::__construct($repository);
        $this->passwordService = $passwordService;
    }

    /**
     * @param ChangePassword $command
     */
    public function __invoke(ChangePassword $command)
    {
        $user = $this->repository->findByUsername($command->getUsername(), true);
        if (!$user) {
            throw new \OutOfBoundsException('User not found');
        }

        $encodedPassword = $this->passwordService->encodePassword($command->getNewPassword());
        $user->setPassword($encodedPassword);

        $this->repository->save($user);
    }
}
