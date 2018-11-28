<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:49
 */

namespace App\Application\User;

use App\Domain\Model\User\UserRepository;

/**
 * Class SetPasswordHandler
 *
 * @package App\Application\User
 */
class SetPasswordHandler extends UserCommandHandler
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
     * @param SetPassword $command
     */
    public function __invoke(SetPassword $command)
    {
        $user = $this->repository->findByResetPasswordToken($command->getResetPasswordToken());
        if (!$user) {
            throw new \OutOfBoundsException('Reset password token not found');
        }

        $encodedPassword = $this->passwordService->encodePassword($command->getNewPassword());
        $user->setPassword($encodedPassword);

        $this->repository->save($user);
    }
}
