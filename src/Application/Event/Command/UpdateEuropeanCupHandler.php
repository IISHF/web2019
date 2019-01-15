<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:03
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EuropeanCup;

/**
 * Class UpdateEuropeanCupHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanCupHandler extends EventCommandHandler
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
        $this->eventRepository->save($cup);
    }
}
