<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.01.17
 * Time: 08:22
 */

namespace App\Infrastructure\Security\MagicLink\Exception;


use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class TokenTamperedException
 *
 * @package App\Infrastructure\Security\MagicLink\Exception
 */
class TokenTamperedException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'The magic link has been tampered.';
    }
}
