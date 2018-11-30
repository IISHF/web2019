<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:21
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\User\User;

/**
 * Class UpdateUser
 *
 * @package App\Application\User\Command
 */
class UpdateUser
{
    use UuidAware, MutableUser, UserProperties;

    /**
     * @param User $user
     * @return self
     */
    public static function update(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getRoles()
        );
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param array  $roles
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        array $roles
    ) {
        $this->id        = $id;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->roles     = $roles;
    }
}
