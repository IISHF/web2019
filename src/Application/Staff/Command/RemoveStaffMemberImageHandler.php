<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\Common\Command\RemoveFileCommandDispatcher;

/**
 * Class RemoveStaffMemberImageHandler
 *
 * @package App\Application\Staff\Command
 */
class RemoveStaffMemberImageHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use RemoveFileCommandDispatcher;

    /**
     * @param RemoveStaffMemberImage $command
     */
    public function __invoke(RemoveStaffMemberImage $command): void
    {
        $member = $this->getStaffMember($command->getId());
        if ($this->dispatchRemoveFileIfExists($member->getImage())) {
            $member->setImage(null);
            $this->memberRepository->save($member);
        }
    }
}
