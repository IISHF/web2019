<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:30
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateStaffMember
 *
 * @package App\Application\Staff\Command
 */
class CreateStaffMember
{
    use UuidAware, StaffMemberProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
