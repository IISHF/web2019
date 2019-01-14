<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:27
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\RecordsEvents;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;

/**
 * Class CreateUserHandler
 *
 * @package App\Application\User\Command
 */
class CreateUserHandler extends UserCommandHandler
{
    use EventEmitter;

    /**
     * @param UserRepository $userRepository
     * @param RecordsEvents  $eventRecorder
     */
    public function __construct(UserRepository $userRepository, RecordsEvents $eventRecorder)
    {
        parent::__construct($userRepository);
        $this->eventRecorder = $eventRecorder;
    }

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
