<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateEuropeanChampionship
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanChampionship implements HasSanctionStatus
{
    use UuidAware, EventProperties, TitleEventProperties;

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
            $championship->getTags(),
            $championship->getSanctionNumber()
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
     * @param string|null $sanctionNumber
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        int $plannedLength,
        ?string $description,
        array $tags,
        ?string $sanctionNumber
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->season         = $season;
        $this->ageGroup       = $ageGroup;
        $this->plannedLength  = $plannedLength;
        $this->description    = $description;
        $this->tags           = $tags;
        $this->sanctionNumber = $sanctionNumber;
        $this->sanctioned     = $sanctionNumber !== null;
    }
}
