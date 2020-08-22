<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\File\Command\RemoveFile;

/**
 * Class RemoveStaffMemberImageHandler
 *
 * @package App\Application\Staff\Command
 */
class RemoveStaffMemberImageHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param RemoveStaffMemberImage $command
     */
    public function __invoke(RemoveStaffMemberImage $command): void
    {
        $member = $this->getStaffMember($command->getId());
        if (($image = $member->getImage()) !== null) {
            $removeFile = RemoveFile::remove($image);
            $this->dispatchCommand($removeFile);
            $member->setImage(null);
            $this->memberRepository->save($member);
        }
    }
}
