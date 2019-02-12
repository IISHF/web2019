<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:13
 */

namespace App\Infrastructure\Event\Venue\Security\Voter;

use App\Domain\Model\Event\Venue\EventVenue;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class EventVenueVoter
 *
 * @package App\Infrastructure\Event\Venue\Security\Voter
 */
class EventVenueVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof EventVenue) {
            return false;
        }

        return \in_array($attribute, ['EVENT_VENUE_EDIT', 'EVENT_VENUE_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var EventVenue $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
