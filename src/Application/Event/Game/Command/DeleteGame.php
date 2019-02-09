<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:13
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Game\Game;

/**
 * Class DeleteGame
 *
 * @package App\Application\Event\Game\Command
 */
class DeleteGame
{
    use UuidAware;

    /**
     * @param Game $game
     * @return self
     */
    public static function delete(Game $game): self
    {
        return new self($game->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
