<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:51
 */

namespace App\Infrastructure\Committee\Security\Voter;

use App\Domain\Model\Committee\Committee;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class CommitteeVoter
 *
 * @package App\Infrastructure\Committee\Security\Voter
 */
class CommitteeVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Committee) {
            return false;
        }

        return \in_array($attribute, ['COMMITTEE_EDIT', 'COMMITTEE_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Committee $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
