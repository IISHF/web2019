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
 * Class UpdateCommitteeMemberHandler
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommitteeMemberHandler extends CommitteeCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param UpdateCommitteeMember $command
     */
    public function __invoke(UpdateCommitteeMember $command): void
    {
        $member = $this->getCommitteeMember($command->getId());
        $member->setFirstName($command->getFirstName())
               ->setLastName($command->getLastName())
               ->setCountry($command->getCountry())
               ->setTitle($command->getTitle())
               ->setTerm($command->getTermType(), $command->getTermSince(), $command->getTermDuration())
               ->setMemberType($command->getMemberType());
        $this->committeeRepository->saveMember($member);

        if (($image = $command->getImage()) !== null) {
            $addImage = AddCommitteeMemberImage::addTo($member, $image);
            $this->dispatchCommand($addImage);
        } elseif ($command->removeImage()) {
            $removeImage = RemoveCommitteeMemberImage::removeFrom($member);
            $this->dispatchCommand($removeImage);
        }
    }
}
