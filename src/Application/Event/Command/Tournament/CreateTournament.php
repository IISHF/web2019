<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:10
 */

namespace App\Application\Event\Command\Tournament;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\HostingProperties;
use App\Application\Event\EventHost;
use DateTimeImmutable;
use DateTimeZone;

/**
 * Class CreateTournament
 *
 * @package App\Application\Event\Command\Tournament
 */
class CreateTournament
{
    use IdAware, EventProperties, HostingProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id        = $id;
        $this->season    = idate('Y');
        $this->host      = new EventHost();
        $this->startDate = new DateTimeImmutable('next Saturday');
        $this->endDate   = $this->startDate->modify('+ 1 day');
        $this->timeZone  = new DateTimeZone('Europe/Berlin');
    }
}
