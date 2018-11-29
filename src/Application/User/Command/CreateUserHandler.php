<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:27
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Class CreateUserHandler
 *
 * @package App\Application\User\Command
 */
class CreateUserHandler extends UserCommandHandler
{
    /**
     * @param CreateUser $command
     * @return string
     */
    public function __invoke(CreateUser $command): string
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

        return $command->getConfirmToken();
    }
}
