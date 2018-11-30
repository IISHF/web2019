<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:27
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CreateUserHandler
 *
 * @package App\Application\User\Command
 */
class CreateUserHandler extends UserCommandHandler
{
    /**
     * @var MessageBusInterface
     */
    private $eventBus;

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
        $this->repository->save($user);
        $this->eventBus->dispatch(UserCreated::created($user, $command->getConfirmToken()));
    }
}
