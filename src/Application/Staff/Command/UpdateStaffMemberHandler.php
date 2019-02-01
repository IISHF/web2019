<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:36
 */

namespace App\Application\Staff\Command;

/**
 * Class UpdateStaffMemberHandler
 *
 * @package App\Application\Staff\Command
 */
class UpdateStaffMemberHandler extends StaffMemberCommandHandler
{
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
    }
}
