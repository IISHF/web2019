<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-22
 * Time: 07:45
 */

namespace App\Application\Event\Command;

use App\Application\Event\EventHost;

/**
 * Interface HasAnnouncementStatus
 *
 * @package App\Application\Event\Command
 */
interface HasAnnouncementStatus extends HasSanctionStatus
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
     * @return string
     */
    public function getVenue(): string;

    /**
     * @param string $venue
     */
    public function setVenue(string $venue);
}
