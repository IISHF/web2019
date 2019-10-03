<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:29
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\IdAware;

/**
 * Class CreateConfirmedUser
 *
 * @package App\Application\User\Command
 */
class CreateConfirmedUser
{
    use IdAware, PasswordAware, UserProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }
}
