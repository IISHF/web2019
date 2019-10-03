<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:03
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\EventEmittingHandler;

/**
 * Class UnconfirmUserHandler
 *
 * @package App\Application\User\Command
 */
class UnconfirmUserHandler extends UserCommandHandler implements EventEmittingHandler
{
    use EventEmitter;

    /**
     * @param UnconfirmUser $command
     */
    public function __invoke(UnconfirmUser $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        $user->markUserAsUnconfirmed($command->getConfirmToken());
        $this->userRepository->save($user);
        $this->emitEvent(UserUnconfirmed::unconfirmed($user, $command->getConfirmToken()));
    }
}
