<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;

/**
 * Class CreateCommitteeMemberHandler
 *
 * @package App\Application\Committee\Command
 */
class CreateCommitteeMemberHandler extends CommitteeCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

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
            $command->getMemberType(),
            $command->getTitle(),
            $command->getTermType(),
            $command->getTermSince(),
            $command->getTermDuration(),
        );
        $member->setFirstTerm($command->getFirstTerm());
        $this->committeeRepository->saveMember($member);

        if (($image = $command->getImage()) !== null) {
            $addImage = AddCommitteeMemberImage::addTo($member, $image);
            $this->dispatchCommand($addImage);
        }
    }
}
