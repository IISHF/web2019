<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:18
 */

namespace App\Infrastructure\Event\Security\Voter;

use App\Domain\Model\Event\TitleEvent;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class TitleEventVoter
 *
 * @package App\Infrastructure\Event\Security\Voter
 */
class TitleEventVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof TitleEvent) {
            return false;
        }

        return \in_array($attribute, ['EVENT_EDIT', 'EVENT_DELETE', 'EVENT_ANNOUNCE', 'EVENT_SANCTION']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var TitleEvent $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
