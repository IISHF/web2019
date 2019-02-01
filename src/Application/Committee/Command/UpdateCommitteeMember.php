<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:09
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Committee\CommitteeMember;

/**
 * Class UpdateCommitteeMember
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommitteeMember
{
    use UuidAware, CommitteeMemberProperties;

    /**
     * @param CommitteeMember $member
     * @return self
     */
    public static function update(CommitteeMember $member): self
    {
        return new self(
            $member->getId(),
            $member->getFirstName(),
            $member->getLastName(),
            $member->getCountry()
        );
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $country
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $country
    ) {
        $this->id        = $id;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->country   = $country;
    }
}
