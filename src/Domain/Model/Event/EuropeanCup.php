<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 13:15
 */

namespace App\Domain\Model\Event;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class EuropeanCup
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity()
 */
class EuropeanCup extends TitleEvent
{
    /**
     * @ORM\Column(name="planned_teams", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $plannedTeams;

    /**
     * @param string      $id
     * @param string      $name
     * @param string      $slug
     * @param int         $season
     * @param string      $ageGroup
     * @param int         $plannedLength
     * @param int         $plannedTeams
     * @param string|null $description
     * @param array       $tags
     */
    protected function __construct(
        string $id,
        string $name,
        string $slug,
        int $season,
        string $ageGroup,
        int $plannedLength,
        int $plannedTeams,
        ?string $description,
        array $tags
    ) {
        parent::__construct($id, $name, $slug, $season, $ageGroup, $plannedLength, $description, $tags);

        $this->setPlannedTeams($plannedTeams);
    }

    /**
     * @return int
     */
    public function getPlannedTeams(): int
    {
        return $this->plannedTeams;
    }

    /**
     * @param int $plannedTeams
     * @return $this
     */
    public function setPlannedTeams(int $plannedTeams): self
    {
        Assert::range($plannedTeams, 2, 99);
        $this->plannedTeams = $plannedTeams;
        return $this;
    }
}
