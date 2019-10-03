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
 * Class RecordResult
 *
 * @package App\Application\Event\Game\Command
 */
class RecordResult
{
    use IdAware, ResultProperties;

    /**
     * @param Game $game
     * @return self
     */
    public static function record(Game $game): self
    {
        return new self(
            $game->getId(),
            $game->getResult()->getHomeGoals(),
            $game->getResult()->getAwayGoals()
        );
    }

    /**
     * @param string   $id
     * @param int|null $homeGoals
     * @param int|null $awayGoals
     */
    private function __construct(string $id, ?int $homeGoals, ?int $awayGoals)
    {
        $this->id        = $id;
        $this->homeGoals = $homeGoals;
        $this->awayGoals = $awayGoals;
    }
}
