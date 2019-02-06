<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

/**
 * Class CreateCommitteeMemberHandler
 *
 * @package App\Application\Committee\Command
 */
class CreateCommitteeMemberHandler extends CommitteeCommandHandler
{
    /**
     * @param CreateCommitteeMember $command
     */
    public function __invoke(CreateCommitteeMember $command): void
    {
        $committee = $this->getCommittee($command->getCommitteeId());
        $member    = $committee->createMember(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getCountry(),
            $command->getTitle(),
            $command->getTermType(),
            $command->getTermSince(),
            $command->getTermDuration()
        );
        $this->committeeRepository->saveMember($member);
    }
}
