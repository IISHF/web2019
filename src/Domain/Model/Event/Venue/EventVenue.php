<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 14:44
 */

namespace App\Domain\Model\Event\Venue;

use App\Domain\Model\Common\Address;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class EventVenue
 *
 * @package App\Domain\Model\Event\Venue
 *
 * @ORM\Entity(repositoryClass="EventVenueRepository")
 * @ORM\Table(
 *      name="event_venues",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_venue_name", columns={"name"})
 *      }
 * )
 */
class EventVenue
{
    use HasId, CreateTracking, UpdateTracking;

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
     * @param string|null $postalCode
     * @param string      $city
     * @param string      $country
     * @param string|null $rinkInfo
     */
    public function __construct(
        string $id,
        string $name,
        ?string $address1,
        ?string $address2,
        ?string $state,
        ?string $postalCode,
        string $city,
        string $country,
        ?string $rinkInfo
    ) {
        $this->setId($id)
             ->setName($name)
             ->setRinkInfo($rinkInfo)
             ->initCreateTracking()
             ->initUpdateTracking();
        $this->address = new Address($address1, $address2, $state, $postalCode, $city, $country);
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
        Assert::nullOrLengthBetween($rinkInfo, 1, 65535);
        $this->rinkInfo = $rinkInfo;
        return $this;
    }
}
