<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

/**
 * Class UpdateCommitteeMemberHandler
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommitteeMemberHandler extends CommitteeCommandHandler
{
    /**
     * @param UpdateCommitteeMember $command
     */
    public function __invoke(UpdateCommitteeMember $command): void
    {
        $member = $this->getCommitteeMember($command->getId());
        $member->setFirstName($command->getFirstName())
               ->setLastName($command->getLastName())
               ->setCountry($command->getCountry())
               ->setTitle($command->getTitle());
        $this->committeeRepository->saveMember($member);
    }
}
