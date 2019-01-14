<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:03
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\RecordsEvents;
use App\Domain\Model\User\UserRepository;

/**
 * Class UnconfirmUserHandler
 *
 * @package App\Application\User\Command
 */
class UnconfirmUserHandler extends UserCommandHandler
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
