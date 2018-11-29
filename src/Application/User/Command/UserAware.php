<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:19
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Trait UserAware
 *
 * @package App\Application\User\Command
 */
trait UserAware
{
    /**
     * @var User
     */
    private $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
