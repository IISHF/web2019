<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:17
 */

namespace App\Infrastructure\Event\Security\Voter;

use App\Domain\Model\Event\Tournament;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

/**
 * Class TournamentVoter
 *
 * @package App\Infrastructure\Event\Security\Voter
 */
class TournamentVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Tournament) {
            return false;
        }

        return in_array($attribute, ['EVENT_EDIT', 'EVENT_DELETE', 'EVENT_SANCTION']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Tournament $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
