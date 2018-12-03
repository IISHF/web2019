<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.01.17
 * Time: 08:30
 */

namespace App\Infrastructure\Security\MagicLink\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class TokenNotFoundException
 *
 * @package App\Infrastructure\Security\MagicLink\Exception
 */
class TokenNotFoundException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $message = 'The token does not exist.',
        int $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'The magic link does not exist or it has been used once already.';
    }
}
