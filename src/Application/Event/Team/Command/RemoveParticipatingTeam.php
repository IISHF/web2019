<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:12
 */

namespace App\Application\Event\Team\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Team\ParticipatingTeam;

/**
 * Class RemoveParticipatingTeam
 *
 * @package App\Application\Event\Team\Command
 */
class RemoveParticipatingTeam
{
    use UuidAware;

    /**
     * @param ParticipatingTeam $team
     * @return self
     */
    public static function remove(ParticipatingTeam $team): self
    {
        return new self($team->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
