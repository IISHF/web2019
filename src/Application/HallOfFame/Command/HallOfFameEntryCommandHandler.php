<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:06
 */

namespace App\Application\HallOfFame\Command;

use App\Domain\Model\HallOfFame\HallOfFameEntry;
use App\Domain\Model\HallOfFame\HallOfFameRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class HallOfFameEntryCommandHandler
 *
 * @package App\Application\HallOfFame\Command
 */
abstract class HallOfFameEntryCommandHandler implements MessageHandlerInterface
{
    /**
     * @var HallOfFameRepository
     */
    protected $hallOfFameRepository;

    /**
     * @param HallOfFameRepository $hallOfFameRepository
     */
    public function __construct(HallOfFameRepository $hallOfFameRepository)
    {
        $this->hallOfFameRepository = $hallOfFameRepository;
    }

    /**
     * @param string $id
     * @return HallOfFameEntry
     */
    protected function getEntry(string $id): HallOfFameEntry
    {
        $entry = $this->hallOfFameRepository->findById($id);
        if (!$entry) {
            throw new \OutOfBoundsException('No hall of fame entry found for id ' . $id);
        }
        return $entry;
    }
}
