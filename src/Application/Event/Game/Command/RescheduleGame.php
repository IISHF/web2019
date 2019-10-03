<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:38
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Event\Game\Game;

/**
 * Class RescheduleGame
 *
 * @package App\Application\Event\Game\Command
 */
class RescheduleGame
{
    use IdAware, ScheduleProperties;

    /**
     * @param Game $game
     * @return self
     */
    public static function reschedule(Game $game): self
    {
        return new self(
            $game->getId(),
            $game->getDateTimeLocal()
        );
    }

    /**
     * @param string             $id
     * @param \DateTimeImmutable $dateTime
     */
    private function __construct(string $id, \DateTimeImmutable $dateTime)
    {
        $this->id       = $id;
        $this->dateTime = $dateTime;
    }
}
