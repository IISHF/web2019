<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 14:44
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\Address;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class EventVenue
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 * @ORM\Table(name="event_venues")
 */
class EventVenue
{
    use CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Embedded(class="App\Domain\Model\Common\Address", columnPrefix="address_")
     *
     * @var Address
     */
    private $address;

    /**
     * @ORM\Column(name="rink_info", type="text", length=65535, nullable=true)
     *
     * @var string|null
     */
    private $rinkInfo;

    /**
     * @param string      $id
     * @param string      $name
     * @param string|null $address1
     * @param string|null $address2
     * @param string|null $state
     * @param string|null $zip
     * @param string      $city
     * @param string      $country
     * @param string|null $rinkInfo
     */
    private function __construct(
        string $id,
        string $name,
        ?string $address1,
        ?string $address2,
        ?string $state,
        ?string $zip,
        string $city,
        string $country,
        ?string $rinkInfo
    ) {
        Assert::uuid($id);

        $this->id      = $id;
        $this->address = new Address($address1, $address2, $state, $zip, $city, $country);
        $this->setName($name)
             ->setRinkInfo($rinkInfo)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
        Assert::lengthBetween($name, 1, 64);
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
