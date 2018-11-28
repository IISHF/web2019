<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:15
 */

namespace App\Application\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserProperties
 *
 * @package App\Application\User
 */
abstract class UserProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $firstName;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $lastName;

    /**
     * @Assert\Type("string")
     * @Assert\Email(mode="strict", checkMX=true, checkHost=true)
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $email;

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string"),
     *     @Assert\NotBlank()
     * })
     *
     * @var string[]
     */
    protected $roles;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
