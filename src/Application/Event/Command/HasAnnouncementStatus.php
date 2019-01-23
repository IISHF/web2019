<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-22
 * Time: 07:45
 */

namespace App\Application\Event\Command;

use App\Application\Event\EventHost;
use App\Domain\Model\Event\EventVenue;

/**
 * Interface HasAnnouncementStatus
 *
 * @package App\Application\Event\Command
 */
interface HasAnnouncementStatus
{
    /**
     * @return bool
     */
    public function isAnnounced(): bool;

    /**
     * @return EventHost
     */
    public function getHost(): EventHost;

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $startDate
     */
    public function setStartDate(\DateTimeImmutable $startDate);

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $endDate
     */
    public function setEndDate(\DateTimeImmutable $endDate);

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue;

    /**
     * @param EventVenue $venue
     */
    public function setVenue(EventVenue $venue);
}
