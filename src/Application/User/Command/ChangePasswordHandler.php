<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:21
 */

namespace App\Application\User\Command;

/**
 * Class ChangePasswordHandler
 *
 * @package App\Application\User\Command
 */
class ChangePasswordHandler extends UserPasswordCommandHandler
{
    /**
     * @param ChangePassword $command
     */
    public function __invoke(ChangePassword $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        $user->changePassword($this->encodePassword($command->getPassword()));
        $this->repository->save($user);
    }
}
