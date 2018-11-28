<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:48
 */

namespace App\Application\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait NewPasswordAware
 *
 * @package App\Application\User
 */
trait NewPasswordAware
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     *
     * @var string
     */
    private $newPassword = '';

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     * @return $this
     */
    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }
}
