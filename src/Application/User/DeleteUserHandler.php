<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:30
 */

namespace App\Application\User;

/**
 * Class DeleteUserHandler
 *
 * @package App\Application\User
 */
class DeleteUserHandler extends UserCommandHandler
{
    /**
     * @param DeleteUser $command
     */
    public function __invoke(DeleteUser $command)
    {
        $this->repository->delete($command->getUser());
    }
}
