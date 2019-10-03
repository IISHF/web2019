<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command\EuropeanChampionship;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\TitleEventProperties;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateEuropeanChampionship
 *
 * @package App\Application\Event\Command\EuropeanChampionship
 */
class UpdateEuropeanChampionship
{
    use IdAware, EventProperties, TitleEventProperties;

    /**
     * @param EuropeanChampionship $championship
     * @return self
     */
    public static function update(EuropeanChampionship $championship): self
    {
        return new self(
            $championship->getId(),
            $championship->getName(),
            $championship->getSeason(),
            $championship->getAgeGroup(),
            $championship->getPlannedLength(),
            $championship->getDescription(),
            $championship->getTags()
        );
    }

    /**
     * @param string      $id
     * @param string      $name
     * @param int         $season
     * @param string      $ageGroup
     * @param int         $plannedLength
     * @param string|null $description
     * @param string[]    $tags
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        int $plannedLength,
        ?string $description,
        array $tags
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->season        = $season;
        $this->ageGroup      = $ageGroup;
        $this->plannedLength = $plannedLength;
        $this->description   = $description;
        $this->tags          = $tags;
    }
}
