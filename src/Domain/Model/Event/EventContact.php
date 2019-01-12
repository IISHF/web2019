<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:48
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\HasEmail;
use App\Domain\Model\Common\MayHavePhoneNumber;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class EventContact
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\MappedSuperclass()
 * @ORM\Embeddable()
 */
class EventContact
{
    use HasEmail, MayHavePhoneNumber;

    /**
     * @ORM\Column(name="club", type="string", length=128)
     *
     * @var string
     */
    private $club;

    /**
     * @ORM\Column(name="name", type="string", length=128)
     *
     * @var string
     */
    private $name;

    /**
     * @param string           $club
     * @param string           $name
     * @param string           $email
     * @param PhoneNumber|null $phoneNumber
     */
    public function __construct(string $club, string $name, string $email, ?PhoneNumber $phoneNumber)
    {
        $this->setClub($club)
             ->setName($name)
             ->setEmail($email)
             ->setPhoneNumber($phoneNumber);
    }

    /**
     * @return string
     */
    public function getClub(): string
    {
        return $this->club;
    }

    /**
     * @param string $club
     * @return $this
     */
    public function setClub(string $club): self
    {
        Assert::lengthBetween($club, 1, 128);
        $this->club = $club;
        return $this;
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
