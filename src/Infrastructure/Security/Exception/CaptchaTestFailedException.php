<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 16:31
 */

namespace App\Infrastructure\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class CaptchaTestFailedException
 *
 * @package App\Infrastructure\Security\Exception
 */
class CaptchaTestFailedException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'You have to pass the CAPTCHA test.';
    }
}
