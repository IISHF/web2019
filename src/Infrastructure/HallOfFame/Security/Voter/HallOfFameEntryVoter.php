<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:20
 */

namespace App\Infrastructure\HallOfFame\Security\Voter;

use App\Domain\Model\HallOfFame\HallOfFameEntry;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use function in_array;

/**
 * Class HallOfFameEntryVoter
 *
 * @package App\Infrastructure\HallOfFame\Security\Voter
 */
class HallOfFameEntryVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof HallOfFameEntry) {
            return false;
        }

        return in_array($attribute, ['HISTORY_ENTRY_EDIT', 'HISTORY_ENTRY_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var HallOfFameEntry $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
