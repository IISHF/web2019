<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:30
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Class PasswordResetRequested
 *
 * @package App\Application\User\Command
 */
class PasswordResetRequested
{
    use UserAware;

    /**
     * @var string
     */
    private $resetPasswordToken;

    /**
     * @param User   $user
     * @param string $resetPasswordToken
     * @return self
     */
    public static function requested(User $user, string $resetPasswordToken): self
    {
        return new self($user, $resetPasswordToken);
    }

    /**
     * @param User   $user
     * @param string $resetPasswordToken
     */
    private function __construct(User $user, string $resetPasswordToken)
    {
        $this->user               = $user;
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }
}
