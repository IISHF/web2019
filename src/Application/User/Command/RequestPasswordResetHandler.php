<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\EventEmittingHandler;
use InvalidArgumentException;

/**
 * Class RequestPasswordResetHandler
 *
 * @package App\Application\User\Command
 */
class RequestPasswordResetHandler extends UserCommandHandler implements EventEmittingHandler
{
    use EventEmitter;

    /**
     * @param RequestPasswordReset $command
     */
    public function __invoke(RequestPasswordReset $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        if (!$user->isConfirmed()) {
            throw new InvalidArgumentException('User has not been confirmed yet.');
        }
        $user->resetPassword($command->getResetPasswordToken());
        $this->userRepository->save($user);
        $this->emitEvent(PasswordResetRequested::requested($user, $command->getResetPasswordToken()));
    }
}
