<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:49
 */

namespace App\Application\User\Command;

/**
 * Class ResetPasswordHandler
 *
 * @package App\Application\User\Command
 */
class ResetPasswordHandler extends UserPasswordCommandHandler
{
    /**
     * @param ResetPassword $command
     */
    public function __invoke(ResetPassword $command): void
    {
        $user = $this->repository->findByResetPasswordToken($command->getResetPasswordToken());
        if (!$user) {
            throw new \OutOfBoundsException('Reset password token not found');
        }

        $user->changePassword($this->encodePassword($command->getPassword()));
        $this->repository->save($user);
    }
}
