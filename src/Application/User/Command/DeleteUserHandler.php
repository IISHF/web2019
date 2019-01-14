<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:30
 */

namespace App\Application\User\Command;

/**
 * Class DeleteUserHandler
 *
 * @package App\Application\User\Command
 */
class DeleteUserHandler extends UserCommandHandler
{
    /**
     * @param DeleteUser $command
     */
    public function __invoke(DeleteUser $command): void
    {
        $user = $this->getUser($command->getId());
        $this->userRepository->delete($user);
    }
}
