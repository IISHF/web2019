<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 10:57
 */

namespace App\Infrastructure\Document\Security\Voter;

use App\Domain\Model\Document\DocumentVersion;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class DocumentVersionVoter
 *
 * @package App\Infrastructure\Document\Security\Voter
 */
class DocumentVersionVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof DocumentVersion) {
            return false;
        }

        return \in_array($attribute, ['DOCUMENT_VERSION_EDIT', 'DOCUMENT_VERSION_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var DocumentVersion $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        if ($attribute === 'DOCUMENT_VERSION_DELETE') {
            return count($subject->getDocument()->getVersions()) > 1;
        }

        return true;
    }
}
