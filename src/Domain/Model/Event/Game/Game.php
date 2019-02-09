<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-08
 * Time: 17:57
 */

namespace App\Domain\Model\Event\Game;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\ParticipatingTeam;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Game
 *
 * @package App\Domain\Model\Event\Game
 *
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(
 *      name="event_games",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_event_game", columns={"event_id", "game_number"})
 *      }
 * )
 */
class Game
{
    use CreateTracking, UpdateTracking;

    /**
     * @var \DateTimeZone|null
     */
    private static $utc;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var Event
     */
    private $event;

    /**
     * @ORM\Column(name="game_number", type="smallint", options={"unsigned":true})
     *
     * @var int
     */
    private $gameNumber;

    /**
     * @ORM\Column(name="game_type", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $gameType;

    /**
     * @ORM\Column(name="date_time_local", type="datetime_immutable")
     *
     * @var \DateTimeImmutable
     */
    private $dateTimeLocal;

    /**
     * @ORM\Column(name="date_time_utc", type="datetime_immutable")
     *
     * @var \DateTimeImmutable
     */
    private $dateTimeUtc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\ParticipatingTeam")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var ParticipatingTeam
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\ParticipatingTeam")
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     *
     * @var ParticipatingTeam
     */
    private $awayTeam;

    /**
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $remarks;

    /**
     * @ORM\Embedded(class="GameResult", columnPrefix="result_")
     *
     * @var GameResult
     */
    private $result;

    /**
     * @return \DateTimeZone
     */
    private static function utc(): \DateTimeZone
    {
        if (!self::$utc) {
            self::$utc = new \DateTimeZone('UTC');
        }
        return self::$utc;
    }

    /**
     * @param string             $id
     * @param Event              $event
     * @param int                $gameNumber
     * @param int                $gameType
     * @param \DateTimeImmutable $dateTime
     * @param ParticipatingTeam  $homeTeam
     * @param ParticipatingTeam  $awayTeam
     * @param string|null        $remarks
     * @param GameResult         $result
     */
    public function __construct(
        string $id,
        Event $event,
        int $gameNumber,
        int $gameType,
        \DateTimeImmutable $dateTime,
        ParticipatingTeam $homeTeam,
        ParticipatingTeam $awayTeam,
        ?string $remarks,
        GameResult $result
    ) {
        Assert::uuid($id);

        $this->id    = $id;
        $this->event = $event;

        $this->setGameNumber($gameNumber)
             ->setGameType($gameType)
             ->setDateTime($dateTime)
             ->setHomeTeam($homeTeam)
             ->setAwayTeam($awayTeam)
             ->setRemarks($remarks)
             ->setResult($result)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return int
     */
    public function getGameNumber(): int
    {
        return $this->gameNumber;
    }

    /**
     * @param int $gameNumber
     * @return $this
     */
    public function setGameNumber(int $gameNumber): self
    {
        Assert::range($gameNumber, 1, 9999);
        $this->gameNumber = $gameNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameType(): int
    {
        return $this->gameType;
    }

    /**
     * @param int $gameType
     * @return $this
     */
    public function setGameType(int $gameType): self
    {
        GameType::assertValidGameType($gameType);
        $this->gameType = $gameType;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTimeLocal(): \DateTimeImmutable
    {
        return $this->dateTimeLocal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTimeUtc(): \DateTimeImmutable
    {
        return $this->dateTimeUtc;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return $this
     */
    public function setDateTime(\DateTimeImmutable $dateTime): self
    {
        if (!$this->event->getTimeZone()) {
            throw new \BadMethodCallException(
                'The event has no time zone set. The game date and time cannot be updated.'
            );
        }
        $dateTimeLocal       = new \DateTimeImmutable($dateTime->format('Y-m-d H:i:s'), $this->event->getTimeZone());
        $this->dateTimeLocal = $dateTimeLocal;
        $this->dateTimeUtc   = $dateTimeLocal->setTimezone(self::utc());
        return $this;
    }

    /**
     * @return ParticipatingTeam
     */
    public function getHomeTeam(): ParticipatingTeam
    {
        return $this->homeTeam;
    }

    /**
     * @param ParticipatingTeam $homeTeam
     * @return $this
     */
    public function setHomeTeam(ParticipatingTeam $homeTeam): self
    {
        Assert::same($this->event, $homeTeam->getEvent());
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return ParticipatingTeam
     */
    public function getAwayTeam(): ParticipatingTeam
    {
        return $this->awayTeam;
    }

    /**
     * @param ParticipatingTeam $awayTeam
     * @return $this
     */
    public function setAwayTeam(ParticipatingTeam $awayTeam): self
    {
        Assert::same($this->event, $awayTeam->getEvent());
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    /**
     * @param string|null $remarks
     * @return $this
     */
    public function setRemarks(?string $remarks): self
    {
        Assert::nullOrLengthBetween($remarks, 1, 255);
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * @return GameResult
     */
    public function getResult(): GameResult
    {
        return $this->result;
    }

    /**
     * @param GameResult $result
     * @return $this
     */
    public function setResult(GameResult $result): self
    {
        $this->result = $result;
        return $this;
    }
}
