<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:48
 */

namespace App\Application\User\Command;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ResetPassword
 *
 * @package App\Application\User\Command
 */
class ResetPassword
{
    use PasswordAware, PasswordChangeOptions;

    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * @param string       $resetPasswordToken
     * @param Request|null $request
     * @param string|null  $modifier
     * @return self
     */
    public static function reset(string $resetPasswordToken, ?Request $request = null, ?string $modifier = null): self
    {
        return new self($resetPasswordToken, $request, $modifier);
    }

    /**
     * @param string       $resetPasswordToken
     * @param Request|null $request
     * @param string|null  $modifier
     */
    private function __construct(string $resetPasswordToken, ?Request $request, ?string $modifier)
    {
        $this->resetPasswordToken = $resetPasswordToken;
        $this->request            = $request;
        $this->modifier           = $modifier;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }
}
