<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-08
 * Time: 18:07
 */

namespace App\Domain\Model\Event\Game;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class GameResult
 *
 * @package App\Domain\Model\Event\Game
 *
 * @ORM\Embeddable()
 */
class GameResult
{
    /**
     * @ORM\Column(name="home_goals", type="smallint", options={"unsigned":true}, nullable=true)
     *
     * @var int|null
     */
    private $homeGoals;

    /**
     * @ORM\Column(name="away_goals", type="smallint", options={"unsigned":true}, nullable=true)
     *
     * @var int|null
     */
    private $awayGoals;

    /**
     * @return self
     */
    public static function noResult(): self
    {
        return self::create(null, null);
    }

    /**
     * @param int $homeGoals
     * @param int $awayGoals
     * @return self
     */
    public static function result(int $homeGoals, int $awayGoals): self
    {
        return self::create($homeGoals, $awayGoals);
    }

    /**
     * @param int|null $homeGoals
     * @param int|null $awayGoals
     * @return self
     */
    public static function create(?int $homeGoals, ?int $awayGoals): self
    {
        return new self($homeGoals, $awayGoals);
    }

    /**
     * @param int|null $homeGoals
     * @param int|null $awayGoals
     */
    private function __construct(?int $homeGoals, ?int $awayGoals)
    {
        if ($homeGoals !== null) {
            Assert::notNull($awayGoals);
        }
        if ($awayGoals !== null) {
            Assert::notNull($homeGoals);
        }
        $this->setHomeGoals($homeGoals)
             ->setAwayGoals($awayGoals);
    }

    /**
     * @return int|null
     */
    public function getHomeGoals(): ?int
    {
        return $this->homeGoals;
    }

    /**
     * @param int|null $homeGoals
     * @return $this
     */
    private function setHomeGoals(?int $homeGoals): self
    {
        Assert::nullOrRange($homeGoals, 0, 99);
        $this->homeGoals = $homeGoals;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwayGoals(): ?int
    {
        return $this->awayGoals;
    }

    /**
     * @param int|null $awayGoals
     * @return $this
     */
    private function setAwayGoals(?int $awayGoals): self
    {
        Assert::nullOrRange($awayGoals, 0, 99);
        $this->awayGoals = $awayGoals;
        return $this;
    }

    /**
     * @return bool
     */
    public function isResult(): bool
    {
        return $this->homeGoals !== null && $this->awayGoals !== null;
    }
}
