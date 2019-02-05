<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:16
 */

namespace App\Application\HallOfFame\Command;

/**
 * Class DeleteHallOfFameEntryHandler
 *
 * @package App\Application\HallOfFame\Command
 */
class DeleteHallOfFameEntryHandler extends HallOfFameEntryCommandHandler
{
    /**
     * @param DeleteHallOfFameEntry $command
     */
    public function __invoke(DeleteHallOfFameEntry $command): void
    {
        $entry = $this->getEntry($command->getId());
        $this->hallOfFameRepository->delete($entry);
    }
}
