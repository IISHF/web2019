<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:16
 */

namespace App\Domain\Model\Event;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

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
        array $tags
    ) {
        parent::__construct($id, $name, $slug, $season, $ageGroup, $tags);

        $this->setHost($host)
             ->setDate($startDate, $endDate)
             ->setVenue($venue);
    }

    /**
     * @return EventHost
     */
    public function getHost(): EventHost
    {
        return parent::getHost();
    }

    /**
     * {@inheritdoc)
     */
    public function setHost(?EventHost $host): Event
    {
        Assert::notNull($host);
        return parent::setHost($host);
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

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue
    {
        return parent::getVenue();
    }

    /**
     * {@inheritdoc)
     */
    public function setVenue(?EventVenue $venue): Event
    {
        Assert::notNull($venue);
        return parent::setVenue($venue);
    }
}
