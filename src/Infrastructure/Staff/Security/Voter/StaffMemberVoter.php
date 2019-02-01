<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:47
 */

namespace App\Infrastructure\Staff\Security\Voter;

use App\Domain\Model\Staff\StaffMember;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class StaffMemberVoter
 *
 * @package App\Infrastructure\Staff\Security\Voter
 */
class StaffMemberVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof StaffMember) {
            return false;
        }

        return \in_array($attribute, ['STAFF_MEMBER_EDIT', 'STAFF_MEMBER_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var StaffMember $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
