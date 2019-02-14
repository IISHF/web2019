<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:16
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Event\Venue\EventVenue;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tournament
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 */
class Tournament extends Event
{
    /**
     * @param string             $id
     * @param string             $name
     * @param string             $slug
     * @param int                $season
     * @param string             $ageGroup
     * @param EventHost          $host
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param EventVenue         $venue
     * @param \DateTimeZone      $timeZone
     * @param array              $tags
     */
    public function __construct(
        string $id,
        string $name,
        string $slug,
        int $season,
        string $ageGroup,
        EventHost $host,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        EventVenue $venue,
        \DateTimeZone $timeZone,
        array $tags
    ) {
        parent::__construct($id, $name, $slug, $season, $ageGroup, $tags);

        $this->setHost($host)
             ->setDate($startDate, $endDate, $timeZone)
             ->setVenue($venue);
    }
}
