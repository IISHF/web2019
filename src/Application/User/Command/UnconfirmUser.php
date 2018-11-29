<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:00
 */

namespace App\Application\User\Command;

/**
 * Class UnconfirmUser
 *
 * @package App\Application\User\Command
 */
class UnconfirmUser
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @param string $email
     * @return self
     */
    public static function unconfirm(string $email): self
    {
        return new self($email, bin2hex(random_bytes(32)));
    }

    /**
     * @param string $email
     * @param string $confirmToken
     */
    private function __construct(string $email, string $confirmToken)
    {
        $this->email        = $email;
        $this->confirmToken = $confirmToken;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }
}
