<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:03
 */

namespace App\Application\User\Command;

/**
 * Class UnconfirmUserHandler
 *
 * @package App\Application\User\Command
 */
class UnconfirmUserHandler extends UserCommandHandler
{
    /**
     * @param UnconfirmUser $command
     * @return string
     */
    public function __invoke(UnconfirmUser $command): string
    {
        $user = $this->getUserByEmail($command->getEmail());
        $user->markUserAsUnconfirmed($command->getConfirmToken());
        $this->repository->save($user);
        return $command->getConfirmToken();
    }
}
