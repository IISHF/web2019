<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:50
 */

namespace App\Application\User;

use App\Domain\Model\User\UserRepository;

/**
 * Class ConfirmUserHandler
 *
 * @package App\Application\User
 */
class ConfirmUserHandler extends UserCommandHandler
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
     * @param ConfirmUser $command
     */
    public function __invoke(ConfirmUser $command)
    {
        $user = $this->repository->findByConfirmToken($command->getConfirmToken());
        if (!$user) {
            throw new \OutOfBoundsException('Confirm token not found');
        }

        $encodedPassword = $this->passwordService->encodePassword($command->getNewPassword());
        $user->setPassword($encodedPassword);

        $this->repository->save($user);
    }
}
