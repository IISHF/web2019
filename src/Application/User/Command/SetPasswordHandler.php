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
class SetPasswordHandler extends UserPasswordCommandHandler
{
    /**
     * @param SetPassword $command
     */
    public function __invoke(SetPassword $command): void
    {
        $user = $this->getUserByEmail($command->getEmail());
        $user->changePassword($this->encodePassword($command->getPassword()));
        $this->userRepository->save($user);
    }
}
