<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:13
 */

namespace App\Application\Common;

use App\Domain\Model\Common\ContactPerson as DomainContactPerson;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactPerson
 *
 * @package App\Application\Common
 */
class ContactPerson
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name = '';

    /**
     * @Assert\Type("string")
     * @Assert\Email(mode="strict")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $email = '';

    /**
     * @Assert\Type("libphonenumber\PhoneNumber")
     * @AssertPhoneNumber()
     * @Assert\Length(max=128)
     *
     * @var PhoneNumber|null
     */
    private $phoneNumber;

    /**
     * @param DomainContactPerson $contact
     * @return self
     */
    public static function fromContactPersonEntity(DomainContactPerson $contact): self
    {
        return (new self())->setName($contact->getName())
                           ->setEmail($contact->getEmail())
                           ->setPhoneNumber($contact->getPhoneNumber());
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
        $this->name = $name;
        return $this;
    }

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
        $this->email = $email;
        return $this;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @param PhoneNumber|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?PhoneNumber $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }
}
