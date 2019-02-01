<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:42
 */

namespace App\Infrastructure\Committee\Security\Voter;

use App\Domain\Model\Committee\CommitteeMember;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class CommitteeMemberVoter
 *
 * @package App\Infrastructure\Committee\Security\Voter
 */
class CommitteeMemberVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof CommitteeMember) {
            return false;
        }

        return \in_array($attribute, ['COMMITTEE_MEMBER_EDIT', 'COMMITTEE_MEMBER_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var CommitteeMember $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
