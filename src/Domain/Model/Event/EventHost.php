<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:14
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\ContactPerson;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class EventHost
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 * @ORM\Table(name="event_hosts")
 */
class EventHost extends ContactPerson
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
     * @ORM\Column(name="club", type="string", length=128)
     *
     * @var string
     */
    private $club;

    /**
     * @param string           $id
     * @param string           $club
     * @param string           $name
     * @param string           $email
     * @param PhoneNumber|null $phoneNumber
     */
    public function __construct(string $id, string $club, string $name, string $email, ?PhoneNumber $phoneNumber)
    {
        parent::__construct($name, $email, $phoneNumber);

        Assert::uuid($id);

        $this->id = $id;

        $this->setClub($club)
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

}
