<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:20
 */

namespace App\Domain\Model\Common;

use Webmozart\Assert\Assert;

/**
 * Trait HasEmail
 *
 * @package App\Domain\Model\Common
 */
trait HasEmail
{
    /**
     * @ORM\Column(name="email", type="string", length=128)
     *
     * @var string
     */
    private $email;

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
        // @see \Symfony\Component\Validator\Constraints\EmailValidator::PATTERN_LOOSE
        Assert::regex($email, '/^.+\@\S+\.\S+$/');
        Assert::lengthBetween($email, 1, 128);
        $this->email = $email;
        return $this;
    }
}
