<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Committee\CommitteeMember;

/**
 * Class DeleteCommitteeMember
 *
 * @package App\Application\Committee\Command
 */
class DeleteCommitteeMember
{
    use IdAware;

    /**
     * @param CommitteeMember $member
     * @return self
     */
    public static function delete(CommitteeMember $member): self
    {
        return new self($member->getId());
    }
}
