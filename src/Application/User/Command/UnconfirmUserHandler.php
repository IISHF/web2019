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
     * @param UserRepository $repository
     * @param RecordsEvents  $eventRecorder
     */
    public function __construct(UserRepository $repository, RecordsEvents $eventRecorder)
    {
        parent::__construct($repository);
        $this->eventRecorder = $eventRecorder;
    }

    /**
     * @param UnconfirmUser $command
     */
    public function __invoke(UnconfirmUser $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        $user->markUserAsUnconfirmed($command->getConfirmToken());
        $this->repository->save($user);
        $this->emitEvent(UserUnconfirmed::unconfirmed($user, $command->getConfirmToken()));
    }
}
