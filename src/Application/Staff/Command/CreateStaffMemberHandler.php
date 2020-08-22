<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:35
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Model\Staff\StaffMember;

/**
 * Class CreateStaffMemberHandler
 *
 * @package App\Application\Staff\Command
 */
class CreateStaffMemberHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

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

        if (($image = $command->getImage()) !== null) {
            $addImage = AddStaffMemberImage::addTo($member, $image);
            $this->dispatchCommand($addImage);
        }
    }
}
