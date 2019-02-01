<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:31
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Staff\StaffMember;

/**
 * Class UpdateStaffMember
 *
 * @package App\Application\Staff\Command
 */
class UpdateStaffMember
{
    use UuidAware, StaffMemberProperties;

    /**
     * @param StaffMember $member
     * @return self
     */
    public static function update(StaffMember $member): self
    {
        return new self(
            $member->getId(),
            $member->getFirstName(),
            $member->getLastName(),
            $member->getEmail(),
            $member->getTitle(),
            $member->getRoles()
        );
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $title
     * @param array  $roles
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $title,
        array $roles
    ) {
        $this->id        = $id;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->email     = $email;
        $this->title     = $title;
        $this->roles     = $roles;
    }
}
