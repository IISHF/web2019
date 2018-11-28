<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:57
 */

namespace App\Application\User;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class PasswordService
 *
 * @package App\Application\User
 */
class PasswordService
{
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param PasswordEncoderInterface $passwordEncoder
     */
    public function __construct(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string $password
     * @return string
     */
    public function encodePassword(string $password): string
    {
        return $this->passwordEncoder->encodePassword($password, '');
    }
}
