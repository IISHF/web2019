<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:48
 */

namespace App\Domain\Model\Common;

use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class ContactPerson
 *
 * @package App\Domain\Model\Common
 *
 * @ORM\MappedSuperclass()
 * @ORM\Embeddable()
 */
class ContactPerson
{
    use HasEmail, MayHavePhoneNumber;

    /**
     * @ORM\Column(name="name", type="string", length=128)
     *
     * @var string
     */
    private $name;

    /**
     * @param string           $name
     * @param string           $email
     * @param PhoneNumber|null $phoneNumber
     */
    public function __construct(string $name, string $email, ?PhoneNumber $phoneNumber)
    {
        $this->setName($name)
             ->setEmail($email)
             ->setPhoneNumber($phoneNumber);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        Assert::lengthBetween($name, 1, 128);
        $this->name = $name;
        return $this;
    }
}
