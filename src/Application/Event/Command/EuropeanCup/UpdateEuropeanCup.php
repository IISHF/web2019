<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command\EuropeanCup;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\TitleEventProperties;
use App\Domain\Model\Event\EuropeanCup;

/**
 * Class UpdateEuropeanCup
 *
 * @package App\Application\Event\Command\EuropeanCup
 */
class UpdateEuropeanCup
{
    use IdAware, EventProperties, TitleEventProperties, EuropeanCupProperties;

    /**
     * @param EuropeanCup $cup
     * @return self
     */
    public static function update(EuropeanCup $cup): self
    {
        return new self(
            $cup->getId(),
            $cup->getName(),
            $cup->getSeason(),
            $cup->getAgeGroup(),
            $cup->getPlannedLength(),
            $cup->getPlannedTeams(),
            $cup->getDescription(),
            $cup->getTags()
        );
    }

    /**
     * @param string      $id
     * @param string      $name
     * @param int         $season
     * @param string      $ageGroup
     * @param int         $plannedLength
     * @param int         $plannedTeams
     * @param string|null $description
     * @param string[]    $tags
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        int $plannedLength,
        int $plannedTeams,
        ?string $description,
        array $tags
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->season        = $season;
        $this->ageGroup      = $ageGroup;
        $this->plannedLength = $plannedLength;
        $this->plannedTeams  = $plannedTeams;
        $this->description   = $description;
        $this->tags          = $tags;
    }
}
