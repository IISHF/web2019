<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:15
 */

namespace App\Application\Committee\Command;

/**
 * Class DeleteCommitteeMemberHandler
 *
 * @package App\Application\Committee\Command
 */
class DeleteCommitteeMemberHandler extends CommitteeCommandHandler
{
    /**
     * @param DeleteCommitteeMember $command
     */
    public function __invoke(DeleteCommitteeMember $command): void
    {
        $member = $this->getCommitteeMember($command->getId());
        $this->committeeRepository->deleteMember($member);
    }
}
