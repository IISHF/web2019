<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:06
 */

namespace App\Application\User\Command;

use App\Domain\Common\Token;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RequestPasswordReset
 *
 * @package App\Application\User\Command
 */
class RequestPasswordReset
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
    private $email = '';

    /**
     * @param Request|null $request
     * @param string|null  $modifier
     * @return self
     */
    public static function request(?Request $request = null, ?string $modifier = null): self
    {
        return new self(Token::random(32), $request, $modifier);
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
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
