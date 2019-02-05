<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:08
 */

namespace App\Application\HallOfFame\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\HallOfFame\HallOfFameEntry;

/**
 * Class DeleteHallOfFameEntry
 *
 * @package App\Application\HallOfFame\Command
 */
class DeleteHallOfFameEntry
{
    use UuidAware;

    /**
     * @param HallOfFameEntry $entry
     * @return self
     */
    public static function delete(HallOfFameEntry $entry): self
    {
        return new self($entry->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
