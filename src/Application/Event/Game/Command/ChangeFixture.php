<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:39
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Game\Game;

/**
 * Class ChangeFixture
 *
 * @package App\Application\Event\Game\Command
 */
class ChangeFixture
{
    use UuidAware, FixtureProperties;

    /**
     * @param Game $game
     * @return self
     */
    public static function change(Game $game): self
    {
        return new self(
            $game->getId(),
            $game->isHomeTeamProvisional() ? null : $game->getHomeTeam()->getId(),
            $game->isHomeTeamProvisional() ? $game->getHomeTeamProvisional() : null,
            $game->isAwayTeamProvisional() ? null : $game->getAwayTeam()->getId(),
            $game->isAwayTeamProvisional() ? $game->getAwayTeamProvisional() : null
        );
    }

    /**
     * @param string      $id
     * @param string|null $homeTeam
     * @param string|null $homeTeamProvisional
     * @param string|null $awayTeam
     * @param string|null $awayTeamProvisional
     */
    private function __construct(
        string $id,
        ?string $homeTeam,
        ?string $homeTeamProvisional,
        ?string $awayTeam,
        ?string $awayTeamProvisional
    ) {
        $this->id = $id;
        $this->initFixture($homeTeam, $homeTeamProvisional, $awayTeam, $awayTeamProvisional);
    }
}
