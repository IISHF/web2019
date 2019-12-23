<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:56
 */

namespace App\Infrastructure\Event\Game\Security\Voter;

use App\Domain\Model\Event\Game\Game;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

/**
 * Class GameVoter
 *
 * @package App\Infrastructure\Event\Game\Security\Voter
 */
class GameVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Game) {
            return false;
        }

        return in_array($attribute, ['EVENT_GAME_EDIT', 'EVENT_GAME_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Game $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
