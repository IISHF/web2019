<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:16
 */

namespace App\Domain\Model\Event;

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
     * @param EventOrganizer     $organizer
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param EventVenue         $venue
     * @param array              $tags
     */
    protected function __construct(
        string $id,
        string $name,
        string $slug,
        int $season,
        string $ageGroup,
        EventOrganizer $organizer,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        EventVenue $venue,
        array $tags
    ) {
        parent::__construct($id, $name, $slug, $season, $ageGroup, $tags);

        $this->setOrganizer($organizer)
             ->setDate($startDate, $endDate)
             ->setVenue($venue);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        return parent::getStartDate();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable
    {
        return parent::getEndDate();
    }
}
