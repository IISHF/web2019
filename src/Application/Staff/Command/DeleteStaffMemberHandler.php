<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:36
 */

namespace App\Application\Staff\Command;

/**
 * Class DeleteStaffMemberHandler
 *
 * @package App\Application\Staff\Command
 */
class DeleteStaffMemberHandler extends StaffMemberCommandHandler
{
    /**
     * @param DeleteStaffMember $command
     */
    public function __invoke(DeleteStaffMember $command): void
    {
        $member = $this->getStaffMember($command->getId());
        $this->memberRepository->delete($member);
    }
}
