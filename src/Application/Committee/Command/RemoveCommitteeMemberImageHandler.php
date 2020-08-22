<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\Common\Command\RemoveFileCommandDispatcher;

/**
 * Class RemoveCommitteeMemberImageHandler
 *
 * @package App\Application\Committee\Command
 */
class RemoveCommitteeMemberImageHandler extends CommitteeCommandHandler implements CommandDispatchingHandler
{
    use RemoveFileCommandDispatcher;

    /**
     * @param RemoveCommitteeMemberImage $command
     */
    public function __invoke(RemoveCommitteeMemberImage $command): void
    {
        $member = $this->getCommitteeMember($command->getId());
        if ($this->dispatchRemoveFileIfExists($member->getImage())) {
            $member->setImage(null);
            $this->committeeRepository->saveMember($member);
        }
    }
}
