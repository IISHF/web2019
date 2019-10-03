<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:27
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\EventEmittingHandler;
use App\Domain\Model\User\User;

/**
 * Class CreateUserHandler
 *
 * @package App\Application\User\Command
 */
class CreateUserHandler extends UserCommandHandler implements EventEmittingHandler
{
    use EventEmitter;

    /**
     * @param CreateUser $command
     */
    public function __invoke(CreateUser $command): void
    {
        $user = User::create(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getRoles(),
            $command->getConfirmToken()
        );
        $this->userRepository->save($user);
        $this->emitEvent(UserCreated::created($user, $command->getConfirmToken()));
    }
}
