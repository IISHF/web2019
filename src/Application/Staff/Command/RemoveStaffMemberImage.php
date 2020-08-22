<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Staff\StaffMember;

/**
 * Class RemoveStaffMemberImage
 *
 * @package App\Application\Staff\Command
 */
class RemoveStaffMemberImage
{
    use IdAware;

    /**
     * @param StaffMember $member
     * @return self
     */
    public static function removeFrom(StaffMember $member): self
    {
        return new self($member->getId());
    }
}
