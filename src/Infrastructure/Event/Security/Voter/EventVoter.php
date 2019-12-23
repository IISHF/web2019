<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:15
 */

namespace App\Infrastructure\Event\Security\Voter;

use App\Domain\Model\Event\Event;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

/**
 * Class EventVoter
 *
 * @package App\Infrastructure\Event\Security\Voter
 */
class EventVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Event) {
            return false;
        }

        return in_array($attribute, ['EVENT_EDIT', 'EVENT_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Event $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
