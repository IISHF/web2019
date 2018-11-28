<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:21
 */

namespace App\Application\User;

use App\Domain\Model\User\User;

/**
 * Class UpdateUser
 *
 * @package App\Application\User
 */
class UpdateUser extends UserProperties
{
    use UserAware, MutableUserCommand;

    /**
     * @param User $user
     * @return self
     */
    public static function update(User $user): self
    {
        return new self(
            $user,
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getRoles()
        );
    }

    /**
     * @param User   $user
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param array  $roles
     */
    private function __construct(
        User $user,
        string $firstName,
        string $lastName,
        string $email,
        array $roles
    ) {
        $this->user      = $user;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->roles     = $roles;
    }
}
