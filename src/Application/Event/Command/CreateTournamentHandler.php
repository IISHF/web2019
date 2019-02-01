<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:55
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventHost;
use App\Domain\Model\Event\Tournament;
use Ramsey\Uuid\Uuid;

/**
 * Class CreateTournamentHandler
 *
 * @package App\Application\Event\Command
 */
class CreateTournamentHandler extends EventCommandHandler
{
    /**
     * @param CreateTournament $command
     */
    public function __invoke(CreateTournament $command): void
    {
        $host = $command->getHost();
        $cup  = new Tournament(
            $command->getId(),
            $command->getName(),
            $this->findSuitableSlug($command->getSeason(), $command->getName(), null),
            $command->getSeason(),
            $command->getAgeGroup(),
            new EventHost(
                (string)Uuid::uuid4(),
                $host->getClub(),
                $host->getName(),
                $host->getEmail(),
                $host->getPhoneNumber()
            ),
            $command->getStartDate(),
            $command->getEndDate(),
            $this->getVenue($command->getVenue()),
            $command->getTags()
        );
        $this->eventRepository->save($cup);
    }
}
