<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:33
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Staff\StaffMember;

/**
 * Class DeleteStaffMember
 *
 * @package App\Application\Staff\Command
 */
class DeleteStaffMember
{
    use UuidAware;

    /**
     * @param StaffMember $member
     * @return self
     */
    public static function delete(StaffMember $member): self
    {
        return new self($member->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
