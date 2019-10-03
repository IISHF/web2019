<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:16
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Common\Urlizer;

/**
 * Class EventCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class EventCommandHandler extends EventBasedCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param int         $season
     * @param string      $name
     * @param string|null $id
     * @return string
     */
    protected function findSuitableSlug(int $season, string $name, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $name,
            function (string $slug) use ($id, $season) {
                return ($tryEvent = $this->eventRepository->findBySlug($season, $slug)) !== null
                    && $tryEvent->getId() !== $id;
            }
        );
    }
}
