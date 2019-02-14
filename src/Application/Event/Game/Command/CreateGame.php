<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:13
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Event\Command\EventAware;
use App\Domain\Model\Event\Game\GameType;

/**
 * Class CreateGame
 *
 * @package App\Application\Event\Game\Command
 */
class CreateGame
{
    use UuidAware, EventAware, GameProperties, ScheduleProperties, FixtureProperties;

    /**
     * @param string $eventId
     * @return self
     */
    public static function create(string $eventId): self
    {
        return new self(self::createUuid(), $eventId);
    }

    /**
     * @param string $id
     * @param string $eventId
     */
    private function __construct(string $id, string $eventId)
    {
        $this->id       = $id;
        $this->eventId  = $eventId;
        $this->gameType = GameType::GAME_TYPE_FIXED;
        $this->dateTime = new \DateTimeImmutable('tomorrow 9:00');
    }
}
