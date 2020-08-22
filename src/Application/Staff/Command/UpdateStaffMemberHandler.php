<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:36
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;

/**
 * Class UpdateStaffMemberHandler
 *
 * @package App\Application\Staff\Command
 */
class UpdateStaffMemberHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param UpdateStaffMember $command
     */
    public function __invoke(UpdateStaffMember $command): void
    {
        $member = $this->getStaffMember($command->getId());
        $member->setFirstName($command->getFirstName())
               ->setLastName($command->getLastName())
               ->setEmail($command->getEmail())
               ->setTitle($command->getTitle())
               ->setRoles($command->getRoles());
        $this->memberRepository->save($member);

        if (($image = $command->getImage()) !== null) {
            $addImage = AddStaffMemberImage::addTo($member, $image);
            $this->dispatchCommand($addImage);
        } elseif ($command->removeImage()) {
            $removeImage = RemoveStaffMemberImage::removeFrom($member);
            $this->dispatchCommand($removeImage);
        }
    }
}
