<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:35
 */

namespace App\Infrastructure\User\Security\Voter;

use App\Domain\Model\User\User;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class UserVoter
 *
 * @package App\Infrastructure\User\Security\Voter
 */
class UserVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof User) {
            return false;
        }

        return \in_array($attribute, ['USER_EDIT', 'USER_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
