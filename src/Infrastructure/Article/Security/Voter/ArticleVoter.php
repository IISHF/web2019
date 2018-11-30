<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:22
 */

namespace App\Infrastructure\Article\Security\Voter;

use App\Domain\Model\Article\Article;
use App\Infrastructure\Security\Voter\DelegatingVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class ArticleVoter
 *
 * @package App\Infrastructure\Article\Security\Voter
 */
class ArticleVoter extends DelegatingVoter
{
    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Article) {
            return false;
        }

        return \in_array($attribute, ['ARTICLE_EDIT', 'ARTICLE_DELETE']);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Article $subject */
        if (!$this->decide($token, ['ROLE_ADMIN'])) {
            return false;
        }

        if ($attribute === 'ARTICLE_EDIT' && $subject->isLegacyFormat()) {
            return false;
        }

        return true;
    }
}
