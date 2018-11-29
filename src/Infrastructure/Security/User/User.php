<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:38
 */

namespace App\Infrastructure\Security\User;

use App\Domain\Model\User\UserInterface as DomainUser;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @package App\Infrastructure\Security\User
 */
class User implements UserInterface, DomainUser
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var bool
     */
    private $confirmed;

    /**
     * @var Role[]|string[]
     */
    private $roles;

    /**
     * @param string $id
     * @param string $username
     * @param string $password
     * @param bool   $confirmed
     * @param array  $roles
     */
    public function __construct(string $id, string $username, string $password, bool $confirmed, array $roles)
    {
        $this->id        = $id;
        $this->username  = $username;
        $this->password  = $password;
        $this->confirmed = $confirmed;
        $this->roles     = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        $roles   = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }
}
