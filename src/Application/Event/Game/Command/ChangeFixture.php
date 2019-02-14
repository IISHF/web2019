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
            $game->getHomeTeam()->getId(),
            $game->getAwayTeam()->getId()
        );
    }

    /**
     * @param string $id
     * @param string $homeTeam
     * @param string $awayTeam
     */
    private function __construct(string $id, string $homeTeam, string $awayTeam)
    {
        $this->id       = $id;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
    }
}
