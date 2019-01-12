<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:14
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class EventOrganizer
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 * @ORM\Table(name="event_organizers")
 */
class EventOrganizer extends EventContact
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
     * @param string           $id
     * @param string           $club
     * @param string           $name
     * @param string           $email
     * @param PhoneNumber|null $phoneNumber
     */
    public function __construct(string $id, string $club, string $name, string $email, ?PhoneNumber $phoneNumber)
    {
        parent::__construct($club, $name, $email, $phoneNumber);

        Assert::uuid($id);

        $this->id = $id;

        $this->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
