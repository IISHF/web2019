<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 08:58
 */

namespace App\Application\Event\Venue\Command;

use App\Application\Common\Address;
use App\Application\Event\Venue\Validator\UniqueEventVenueName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait EventVenueProperties
 *
 * @package App\Application\Event\Venue\Command
 */
trait EventVenueProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotBlank()
     * @UniqueEventVenueName()
     *
     * @var string
     */
    private $name = '';

    /**
     * @Assert\Type("App\Application\Common\Address")
     * @Assert\NotNull()
     * @Assert\Valid()
     *
     * @var Address
     */
    private $address;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=65535)
     *
     * @var string|null
     */
    private $rinkInfo;

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
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getRinkInfo(): ?string
    {
        return $this->rinkInfo;
    }

    /**
     * @param string|null $rinkInfo
     * @return $this
     */
    public function setRinkInfo(?string $rinkInfo): self
    {
        $this->rinkInfo = $rinkInfo;
        return $this;
    }
}
