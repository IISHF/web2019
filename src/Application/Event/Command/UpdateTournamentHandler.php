<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:17
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\Tournament;

/**
 * Class UpdateTournamentHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateTournamentHandler extends EventCommandHandler
{
    /**
     * @param UpdateTournament $command
     */
    public function __invoke(UpdateTournament $command): void
    {
        $host = $command->getHost();
        /** @var Tournament $tournament */
        $tournament = $this->getEvent($command->getId(), Tournament::class);
        $tournament->setName($command->getName())
                   ->setSeason($command->getSeason())
                   ->setAgeGroup($command->getAgeGroup())
                   ->setDate($command->getStartDate(), $command->getEndDate())
                   ->setVenue($command->getVenue())
                   ->setTags($command->getTags());
        $tournament->getHost()
                   ->setClub($host->getClub())
                   ->setName($host->getName())
                   ->setEmail($host->getEmail())
                   ->setPhoneNumber($host->getPhoneNumber());
        $this->eventRepository->save($tournament);
    }
}
