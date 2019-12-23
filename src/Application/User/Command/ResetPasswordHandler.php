<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:49
 */

namespace App\Application\User\Command;

use OutOfBoundsException;

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
        $user = $this->userRepository->findByResetPasswordToken($command->getResetPasswordToken());
        if (!$user) {
            throw new OutOfBoundsException(
                'No user found for reset password token ' . $command->getResetPasswordToken()
            );
        }
        $user->changePassword($this->encodePassword($command->getPassword()));
        $this->userRepository->save($user);
    }
}
