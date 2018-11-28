<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User;

/**
 * Class ResetPasswordHandler
 *
 * @package App\Application\User
 */
class ResetPasswordHandler extends UserCommandHandler
{
    /**
     * @param ResetPassword $command
     * @return string
     */
    public function __invoke(ResetPassword $command): string
    {
        $user = $this->repository->findByUsername($command->getUsername(), true);
        if (!$user) {
            throw new \OutOfBoundsException('User not found');
        }

        $user->resetPassword($command->getResetPasswordToken());

        $this->repository->save($user);

        return $command->getResetPasswordToken();
    }
}
