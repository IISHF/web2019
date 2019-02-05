<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:07
 */

namespace App\Application\HallOfFame\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateHallOfFameEntry
 *
 * @package App\Application\HallOfFame\Command
 */
class CreateHallOfFameEntry
{
    use UuidAware, HallOfFameEntryProperties;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull()
     *
     * @var bool
     */
    private $championship;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isChampionship(): bool
    {
        return $this->championship;
    }

    /**
     * @param bool $championship
     * @return $this
     */
    public function setChampionship(bool $championship): self
    {
        $this->championship = $championship;
        return $this;
    }
}
