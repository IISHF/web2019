<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Application\Common\Command\RecordsEvents;
use App\Domain\Model\User\UserRepository;

/**
 * Class RequestPasswordResetHandler
 *
 * @package App\Application\User\Command
 */
class RequestPasswordResetHandler extends UserCommandHandler
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
     * @param RequestPasswordReset $command
     */
    public function __invoke(RequestPasswordReset $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        if (!$user->isConfirmed()) {
            throw new \InvalidArgumentException('User has not been confirmed yet.');
        }
        $user->resetPassword($command->getResetPasswordToken());
        $this->userRepository->save($user);
        $this->emitEvent(PasswordResetRequested::requested($user, $command->getResetPasswordToken()));
    }
}
