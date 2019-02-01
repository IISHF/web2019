<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:35
 */

namespace App\Application\Staff\Command;

use App\Domain\Model\Staff\StaffMember;

/**
 * Class CreateStaffMemberHandler
 *
 * @package App\Application\Staff\Command
 */
class CreateStaffMemberHandler extends StaffMemberCommandHandler
{
    /**
     * @param CreateStaffMember $command
     */
    public function __invoke(CreateStaffMember $command): void
    {
        $member = new StaffMember(
            $command->getId(),
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getTitle(),
            $command->getRoles()
        );
        $this->memberRepository->save($member);
    }
}
