<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Committee\CommitteeMember;

/**
 * Class RemoveCommitteeMemberImage
 *
 * @package App\Application\Committee\Command
 */
class RemoveCommitteeMemberImage
{
    use IdAware;

    /**
     * @param CommitteeMember $member
     * @return self
     */
    public static function removeFrom(CommitteeMember $member): self
    {
        return new self($member->getId());
    }
}
