<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:57
 */

namespace App\Infrastructure\Document\Security\Voter;

use App\Domain\Model\Document\Document;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class DocumentVoter
 *
 * @package App\Infrastructure\Document\Security\Voter
 */
class DocumentVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Document) {
            return false;
        }

        return \in_array($attribute, ['DOCUMENT_EDIT', 'DOCUMENT_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Document $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        return true;
    }
}
