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

    private const EVENT_EUROPEAN_CHAMPIONSHIP    = 'European Championship';
    private const EVENT_EUROPEAN_CUP             = 'European Cup';
    private const EVENT_EUROPEAN_CUP_WINNERS_CUP = 'European Cup Winners\' Cup';

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull()
     *
     * @var bool
     */
    private $championship;

    /**
     * @param string $ageGroup
     * @return self
     */
    public static function createEuropeanChampionship(string $ageGroup): self
    {
        return self::create()
                   ->setAgeGroup($ageGroup)
                   ->setChampionship(true)
                   ->setEvent(self::EVENT_EUROPEAN_CHAMPIONSHIP);
    }

    /**
     * @param string $ageGroup
     * @return self
     */
    public static function createEuropeanCup(string $ageGroup): self
    {
        return self::create()
                   ->setAgeGroup($ageGroup)
                   ->setChampionship(true)
                   ->setEvent(self::EVENT_EUROPEAN_CUP);
    }

    /**
     * @param string $ageGroup
     * @return self
     */
    public static function createEuropeanCupWinnersCup(string $ageGroup): self
    {
        return self::create()
                   ->setAgeGroup($ageGroup)
                   ->setChampionship(true)
                   ->setEvent(self::EVENT_EUROPEAN_CUP_WINNERS_CUP);
    }

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
        $this->id     = $id;
        $this->season = idate('Y');
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
