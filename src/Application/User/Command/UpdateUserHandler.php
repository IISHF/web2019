<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:28
 */

namespace App\Application\User\Command;

/**
 * Class UpdateUserHandler
 *
 * @package App\Application\User\Command
 */
class UpdateUserHandler extends UserCommandHandler
{
    /**
     * @param UpdateUser $command
     */
    public function __invoke(UpdateUser $command): void
    {
        $user = $this->getUser($command->getId());
        $user->setFirstName($command->getFirstName())
             ->setLastName($command->getLastName())
             ->setEmail($command->getEmail())
             ->setRoles($command->getRoles());
        $this->userRepository->save($user);
    }
}
