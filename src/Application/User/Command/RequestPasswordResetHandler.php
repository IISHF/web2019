<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class RequestPasswordResetHandler
 *
 * @package App\Application\User\Command
 */
class RequestPasswordResetHandler extends UserCommandHandler
{
    use EventEmitter;

    /**
     * @param UserRepository      $repository
     * @param MessageBusInterface $eventBus
     */
    public function __construct(UserRepository $repository, MessageBusInterface $eventBus)
    {
        parent::__construct($repository);
        $this->eventBus = $eventBus;
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
        $this->repository->save($user);
        $this->emitEvent(PasswordResetRequested::requested($user, $command->getResetPasswordToken()));
    }
}
