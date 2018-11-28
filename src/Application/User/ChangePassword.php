<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:06
 */

namespace App\Application\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class ChangePassword
 *
 * @package App\Application\User
 */
class ChangePassword
{
    use NewPasswordAware, PasswordChangeOptions;

    /**
     * @var string
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * @SecurityAssert\UserPassword()
     *
     * @var string
     */
    private $currentPassword = '';

    /**
     * @param UserInterface $user
     * @param Request|null  $request
     * @param string|null   $modifier
     * @return self
     */
    public static function change(UserInterface $user, ?Request $request = null, ?string $modifier = null): self
    {
        return new self($user->getUsername(), $request, $modifier);
    }

    /**
     * @param string       $username
     * @param Request|null $request
     * @param string|null  $modifier
     */
    private function __construct(string $username, ?Request $request, ?string $modifier)
    {
        $this->username = $username;
        $this->request  = $request;
        $this->modifier = $modifier;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     * @return $this
     */
    public function setCurrentPassword(string $currentPassword): self
    {
        $this->currentPassword = $currentPassword;
        return $this;
    }
}
