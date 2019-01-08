<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:03
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\EventEmitter;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class UnconfirmUserHandler
 *
 * @package App\Application\User\Command
 */
class UnconfirmUserHandler extends UserCommandHandler
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
