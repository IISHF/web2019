<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:00
 */

namespace App\Application\User\Command;

/**
 * Class SetPasswordHandler
 *
 * @package App\Application\User\Command
 */
class SetPasswordHandler extends PasswordCommandHandler
{
    /**
     * @param SetPassword $command
     */
    public function __invoke(SetPassword $command): void
    {
        $user = $this->repository->findByEmail($command->getEmail());
        if (!$user) {
            throw new \OutOfBoundsException('User not found');
        }

        $user->changePassword($this->encodePassword($command->getPassword()));
        $this->repository->save($user);
    }
}
