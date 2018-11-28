<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:04
 */

namespace App\Infrastructure\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class DelegatingVoter
 *
 * @package App\Infrastructure\Security\Voter
 */
abstract class DelegatingVoter extends Voter
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $accessDecisionManager;

    /**
     * @param AccessDecisionManagerInterface $accessDecisionManager
     */
    public function __construct(AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * @param TokenInterface $token
     * @param array          $attributes
     * @param object         $object
     * @return bool
     */
    protected function decide(TokenInterface $token, array $attributes, $object = null): bool
    {
        return $this->accessDecisionManager->decide($token, $attributes, $object);
    }
}
