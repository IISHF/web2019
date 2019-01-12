<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:24
 */

namespace App\Domain\Model\Common;

use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;

/**
 * Trait HasEmail
 *
 * @package App\Domain\Model\Common
 */
trait MayHavePhoneNumber
{
    /**
     * @ORM\Column(name="phone_number", type="phone_number", nullable=true)
     *
     * @var PhoneNumber|null
     */
    private $phoneNumber;

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
