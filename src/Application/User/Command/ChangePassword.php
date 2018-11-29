<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:06
 */

namespace App\Application\User\Command;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class ChangePassword
 *
 * @package App\Application\User\Command
 */
class ChangePassword
{
    use PasswordAware, PasswordChangeOptions;

    /**
     * @var string
     */
    private $email;

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
     * @param string       $email
     * @param Request|null $request
     * @param string|null  $modifier
     */
    private function __construct(string $email, ?Request $request, ?string $modifier)
    {
        $this->email    = $email;
        $this->request  = $request;
        $this->modifier = $modifier;
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
