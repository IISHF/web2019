<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:03
 */

namespace App\Application\Event\Command;

use App\Application\Event\Game\Command\UpdateScheduleTimeZone;
use App\Domain\Model\Event\EuropeanCup;

/**
 * Class UpdateEuropeanCupHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanCupHandler extends TitleEventCommandHandler
{
    /**
     * @param UpdateEuropeanCup $command
     */
    public function __invoke(UpdateEuropeanCup $command): void
    {
        $cup = $this->getEvent($command->getId());
        if (!$cup instanceof EuropeanCup) {
            throw new \InvalidArgumentException('Invalid event - ' . EuropeanCup::class . ' required.');
        }
        $cup->setName($command->getName())
            ->setSeason($command->getSeason())
            ->setAgeGroup($command->getAgeGroup())
            ->setPlannedLength($command->getPlannedLength())
            ->setPlannedTeams($command->getPlannedTeams())
            ->setDescription($command->getDescription())
            ->setTags($command->getTags());
        if ($cup->isAnnounced() || $cup->isSanctioned()) {
            $currentTimeZone = $cup->getTimeZone();
            if ($currentTimeZone->getName() !== $command->getTimeZone()->getName()) {
                $this->dispatchCommand(UpdateScheduleTimeZone::update($cup->getId(), $command->getTimeZone()));
            }

            $cup->setVenue($this->getVenue($command->getVenue()))
                ->setTimeZone($command->getTimeZone());
            $host = $command->getHost();
            $cup->getHost()
                ->setClub($host->getClub())
                ->setName($host->getName())
                ->setEmail($host->getEmail())
                ->setPhoneNumber($host->getPhoneNumber());
        }
        $this->eventRepository->save($cup);
    }
}
