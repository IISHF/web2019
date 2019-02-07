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
            $member->getCountry(),
            $member->getTitle(),
            $member->getTermType(),
            $member->getTermSince(),
            $member->getTermDuration(),
            $member->getMemberType()
        );
    }

    /**
     * @param string      $id
     * @param string      $firstName
     * @param string      $lastName
     * @param string      $country
     * @param string|null $title
     * @param int         $termType
     * @param int|null    $termSince
     * @param int|null    $termDuration
     * @param int         $memberType
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $country,
        ?string $title,
        int $termType,
        ?int $termSince,
        ?int $termDuration,
        int $memberType
    ) {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
        $this->country      = $country;
        $this->title        = $title;
        $this->termType     = $termType;
        $this->termSince    = $termSince;
        $this->termDuration = $termDuration;
        $this->memberType   = $memberType;
    }
}
