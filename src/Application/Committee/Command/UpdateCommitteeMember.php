<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:09
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Committee\CommitteeMember;

/**
 * Class UpdateCommitteeMember
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommitteeMember
{
    use IdAware, CommitteeMemberProperties;

    /**
     * @var bool
     */
    private bool $hasImage;

    /**
     * @var bool
     */
    private bool $removeImage = false;

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
            $member->getMemberType(),
            $member->getTitle(),
            $member->getTermType(),
            $member->getTermSince(),
            $member->getTermDuration(),
            $member->getFirstTerm(),
            $member->getImage() !== null
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
     * @param int|null    $firstTerm
     * @param bool        $hasImage
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $country,
        int $memberType,
        ?string $title,
        int $termType,
        ?int $termSince,
        ?int $termDuration,
        ?int $firstTerm,
        bool $hasImage
    ) {
        $this->id           = $id;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
        $this->country      = $country;
        $this->memberType   = $memberType;
        $this->title        = $title;
        $this->termType     = $termType;
        $this->termSince    = $termSince;
        $this->termDuration = $termDuration;
        $this->firstTerm    = $firstTerm;
        $this->hasImage     = $hasImage;
    }

    /**
     * @return bool
     */
    public function hasImage(): bool
    {
        return $this->hasImage;
    }

    /**
     * @return bool
     */
    public function removeImage(): bool
    {
        return $this->removeImage;
    }

    /**
     * @param bool $removeImage
     * @return $this
     */
    public function setRemoveImage(bool $removeImage): self
    {
        $this->removeImage = $removeImage;
        return $this;
    }
}
