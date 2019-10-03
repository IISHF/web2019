<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:30
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\IdAware;

/**
 * Class CreateStaffMember
 *
 * @package App\Application\Staff\Command
 */
class CreateStaffMember
{
    use IdAware, StaffMemberProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }
}
