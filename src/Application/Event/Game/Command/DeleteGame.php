<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:13
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Event\Game\Game;

/**
 * Class DeleteGame
 *
 * @package App\Application\Event\Game\Command
 */
class DeleteGame
{
    use IdAware;

    /**
     * @param Game $game
     * @return self
     */
    public static function delete(Game $game): self
    {
        return new self($game->getId());
    }
}
