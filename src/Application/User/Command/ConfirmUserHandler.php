<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:50
 */

namespace App\Application\User\Command;

/**
 * Class ConfirmUserHandler
 *
 * @package App\Application\User\Command
 */
class ConfirmUserHandler extends UserPasswordCommandHandler
{
    /**
     * @param ConfirmUser $command
     */
    public function __invoke(ConfirmUser $command): void
    {
        $user = $this->repository->findByConfirmToken($command->getConfirmToken());
        if (!$user) {
            throw new \OutOfBoundsException('No user found for confirmation token ' . $command->getConfirmToken());
        }
        $user->markUserAsConfirmed($this->encodePassword($command->getPassword()));
        $this->repository->save($user);
    }
}
