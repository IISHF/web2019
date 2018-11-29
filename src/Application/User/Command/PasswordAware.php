<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:48
 */

namespace App\Application\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait PasswordAware
 *
 * @package App\Application\User\Command
 */
trait PasswordAware
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=4096)
     *
     * @var string
     */
    private $password = '';

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
