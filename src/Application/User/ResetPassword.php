<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:06
 */

namespace App\Application\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResetPassword
 *
 * @package App\Application\User
 */
class ResetPassword
{
    use PasswordChangeOptions;

    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $username = '';

    /**
     * @param string|null  $resetPasswordToken
     * @param Request|null $request
     * @param string|null  $modifier
     * @return self
     */
    public static function reset(
        ?string $resetPasswordToken = null,
        ?Request $request = null,
        ?string $modifier = null
    ): self {
        return new self($resetPasswordToken ?? bin2hex(random_bytes(32)), $request, $modifier);
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }
}
