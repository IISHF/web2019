<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:33
 */

namespace App\Application\Event;

use App\Application\Common\ContactPerson;
use App\Domain\Model\Event\EventHost as DomainEventHost;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EventHost
 *
 * @package App\Application\Event
 */
class EventHost extends ContactPerson
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $club;

    /**
     * @param DomainEventHost $host
     * @return self
     */
    public static function updateHost(DomainEventHost $host): self
    {
        return (new self())->setClub($host->getClub())
                           ->setName($host->getName())
                           ->setEmail($host->getEmail())
                           ->setPhoneNumber($host->getPhoneNumber());
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
        $this->club = $club;
        return $this;
    }
}
