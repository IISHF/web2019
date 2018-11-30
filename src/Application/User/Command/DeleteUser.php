<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:24
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\User\User;

/**
 * Class DeleteUser
 *
 * @package App\Application\User\Command
 */
class DeleteUser
{
    use UuidAware;

    /**
     * @param User $user
     * @return self
     */
    public static function delete(User $user): self
    {
        return new self($user->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
