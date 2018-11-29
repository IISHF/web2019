<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:24
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Class DeleteUser
 *
 * @package App\Application\User\Command
 */
class DeleteUser
{
    use UserAware;

    /**
     * @param User $user
     * @return self
     */
    public static function delete(User $user): self
    {
        return new self($user);
    }

    /**
     * @param User $user
     */
    private function __construct(User $user)
    {
        $this->user = $user;
    }
}
