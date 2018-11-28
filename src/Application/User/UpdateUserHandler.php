<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:28
 */

namespace App\Application\User;

/**
 * Class UpdateUserHandler
 *
 * @package App\Application\User
 */
class UpdateUserHandler extends UserCommandHandler
{
    /**
     * @param UpdateUser $command
     */
    public function __invoke(UpdateUser $command)
    {
        $user = $command->getUser();
        $user->setFirstName($command->getFirstName())
             ->setLastName($command->getLastName())
             ->setEmail($command->getEmail())
             ->setRoles($command->getRoles());

        $this->repository->save($user);
    }
}
