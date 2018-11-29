<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User\Command;

/**
 * Class RequestPasswordResetHandler
 *
 * @package App\Application\User\Command
 */
class RequestPasswordResetHandler extends UserCommandHandler
{
    /**
     * @param RequestPasswordReset $command
     * @return string
     */
    public function __invoke(RequestPasswordReset $command): string
    {
        $user = $this->repository->findByEmail($command->getEmail());
        if (!$user) {
            throw new \OutOfBoundsException('User not found');
        }

        $user->resetPassword($command->getResetPasswordToken());
        $this->repository->save($user);

        return $command->getResetPasswordToken();
    }
}
