<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:06
 */

namespace App\Infrastructure\NationalGoverningBody\Security\Voter;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class NationalGoverningBodyVoter
 *
 * @package App\Infrastructure\NationalGoverningBody\Security\Voter
 */
class NationalGoverningBodyVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof NationalGoverningBody) {
            return false;
        }

        return \in_array($attribute, ['NATIONAL_GOVERNING_BODY_EDIT', 'NATIONAL_GOVERNING_BODY_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var NationalGoverningBody $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }
        return true;
    }
}
