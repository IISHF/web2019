<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:30
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Class CreateConfirmedUserHandler
 *
 * @package App\Application\User\Command
 */
class CreateConfirmedUserHandler extends UserPasswordCommandHandler
{
    /**
     * @param CreateConfirmedUser $command
     */
    public function __invoke(CreateConfirmedUser $command): void
    {
        $user = User::createConfirmed(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getRoles(),
            $this->encodePassword($command->getPassword())
        );
        $this->repository->save($user);
    }
}
