<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-11
 * Time: 07:24
 */

namespace App\Application\Event\Game\Command;

use App\Application\Event\Command\EventAware;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UpdateScheduleTimeZone
 *
 * @package App\Application\Event\Game\Command
 */
class UpdateScheduleTimeZone
{
    use EventAware;

    /**
     * @Assert\Type("DateTimeZone")
     * @Assert\NotNull()
     *
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * @param string        $eventId
     * @param DateTimeZone $timeZone
     * @return self
     */
    public static function update(string $eventId, DateTimeZone $timeZone): self
    {
        return new self($eventId, $timeZone);
    }

    /**
     * UpdateScheduleTimeZone constructor.
     *
     * @param string        $eventId
     * @param DateTimeZone $timeZone
     */
    private function __construct(string $eventId, DateTimeZone $timeZone)
    {
        $this->eventId  = $eventId;
        $this->timeZone = $timeZone;
    }

    /**
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        return $this->timeZone;
    }
}
