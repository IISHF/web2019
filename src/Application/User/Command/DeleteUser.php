<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:24
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\User\User;

/**
 * Class DeleteUser
 *
 * @package App\Application\User\Command
 */
class DeleteUser
{
    use IdAware;

    /**
     * @param User $user
     * @return self
     */
    public static function delete(User $user): self
    {
        return new self($user->getId());
    }
}
