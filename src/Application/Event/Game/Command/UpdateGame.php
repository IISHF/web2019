<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:14
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Game\Game;

/**
 * Class UpdateGame
 *
 * @package App\Application\Event\Game\Command
 */
class UpdateGame
{
    use UuidAware, GameProperties, ScheduleProperties, FixtureProperties, ResultProperties;

    /**
     * @param Game $game
     * @return self
     */
    public static function update(Game $game): self
    {
        return new self(
            $game->getId(),
            $game->getGameType(),
            $game->getDateTimeLocal(),
            $game->isHomeTeamProvisional() ? null : $game->getHomeTeam()->getId(),
            $game->isHomeTeamProvisional() ? $game->getHomeTeamProvisional() : null,
            $game->isAwayTeamProvisional() ? null : $game->getAwayTeam()->getId(),
            $game->isAwayTeamProvisional() ? $game->getAwayTeamProvisional() : null,
            $game->getRemarks(),
            $game->getResult()->getHomeGoals(),
            $game->getResult()->getAwayGoals()
        );
    }

    /**
     * @param string             $id
     * @param int                $gameType
     * @param \DateTimeImmutable $dateTime
     * @param string|null        $homeTeam
     * @param string|null        $homeTeamProvisional
     * @param string|null        $awayTeam
     * @param string|null        $awayTeamProvisional
     * @param string|null        $remarks
     * @param int|null           $homeGoals
     * @param int|null           $awayGoals
     */
    private function __construct(
        string $id,
        int $gameType,
        \DateTimeImmutable $dateTime,
        ?string $homeTeam,
        ?string $homeTeamProvisional,
        ?string $awayTeam,
        ?string $awayTeamProvisional,
        ?string $remarks,
        ?int $homeGoals,
        ?int $awayGoals
    ) {
        $this->id        = $id;
        $this->gameType  = $gameType;
        $this->dateTime  = $dateTime;
        $this->remarks   = $remarks;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
        $this->initFixture($homeTeam, $homeTeamProvisional, $awayTeam, $awayTeamProvisional);
    }
}
