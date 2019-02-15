<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-08
 * Time: 17:57
 */

namespace App\Domain\Model\Event\Game;

use App\Domain\Common\DateTime;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\Team\ParticipatingTeam;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Game
 *
 * @package App\Domain\Model\Event\Game
 *
 * @ORM\Entity(repositoryClass="GameRepository")
 * @ORM\Table(name="event_games")
 */
class Game
{
    use CreateTracking, UpdateTracking;

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
    private $gameNumber = 0;

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
     * @ORM\Column(name="time_zone", type="string", length=32)
     *
     * @var string
     */
    private $timeZone;

    /**
     * @var \DateTimeZone|null
     */
    private $timeZoneInstance;

    /**
     * @ORM\Column(name="date_time_utc", type="datetime_immutable")
     *
     * @var \DateTimeImmutable
     */
    private $dateTimeUtc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Team\ParticipatingTeam")
     * @ORM\JoinColumn(name="home_team_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     *
     * @var ParticipatingTeam|null
     */
    private $homeTeam;

    /**
     * @ORM\Column(name="home_team_provisional", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $homeTeamProvisional;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Team\ParticipatingTeam")
     * @ORM\JoinColumn(name="away_team_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     *
     * @var ParticipatingTeam|null
     */
    private $awayTeam;

    /**
     * @ORM\Column(name="away_team_provisional", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $awayTeamProvisional;

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
     * @param string                   $id
     * @param Event                    $event
     * @param int                      $gameType
     * @param \DateTimeImmutable       $dateTime
     * @param \DateTimeZone            $timeZone
     * @param ParticipatingTeam|string $homeTeam
     * @param ParticipatingTeam|string $awayTeam
     * @param string|null              $remarks
     * @return Game
     */
    public static function create(
        string $id,
        Event $event,
        int $gameType,
        \DateTimeImmutable $dateTime,
        \DateTimeZone $timeZone,
        $homeTeam,
        $awayTeam,
        ?string $remarks
    ): self {
        return new self(
            $id,
            $event,
            $gameType,
            $dateTime,
            $timeZone,
            $homeTeam instanceof ParticipatingTeam ? $homeTeam : null,
            $homeTeam instanceof ParticipatingTeam ? null : $homeTeam,
            $awayTeam instanceof ParticipatingTeam ? $awayTeam : null,
            $awayTeam instanceof ParticipatingTeam ? null : $awayTeam,
            $remarks,
            GameResult::noResult()
        );
    }

    /**
     * @param string             $id
     * @param Event              $event
     * @param int                $gameType
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $timeZone
     * @param ParticipatingTeam  $homeTeam
     * @param ParticipatingTeam  $awayTeam
     * @param string|null        $remarks
     * @return Game
     */
    public static function createWithFixture(
        string $id,
        Event $event,
        int $gameType,
        \DateTimeImmutable $dateTime,
        \DateTimeZone $timeZone,
        ParticipatingTeam $homeTeam,
        ParticipatingTeam $awayTeam,
        ?string $remarks
    ): self {
        return new self(
            $id,
            $event,
            $gameType,
            $dateTime,
            $timeZone,
            $homeTeam,
            null,
            $awayTeam,
            null,
            $remarks,
            GameResult::noResult()
        );
    }

    /**
     * @param string             $id
     * @param Event              $event
     * @param int                $gameType
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $timeZone
     * @param string             $homeTeam
     * @param string             $awayTeam
     * @param string|null        $remarks
     * @return Game
     */
    public static function createWithProvisionalFixture(
        string $id,
        Event $event,
        int $gameType,
        \DateTimeImmutable $dateTime,
        \DateTimeZone $timeZone,
        string $homeTeam,
        string $awayTeam,
        ?string $remarks
    ): self {
        return new self(
            $id,
            $event,
            $gameType,
            $dateTime,
            $timeZone,
            null,
            $homeTeam,
            null,
            $awayTeam,
            $remarks,
            GameResult::noResult()
        );
    }

    /**
     * @param string             $id
     * @param Event              $event
     * @param int                $gameType
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $timeZone
     * @param ParticipatingTeam  $homeTeam
     * @param string|null        $homeTeamProvisional
     * @param ParticipatingTeam  $awayTeam
     * @param string|null        $awayTeamProvisional
     * @param string|null        $remarks
     * @param GameResult         $result
     */
    private function __construct(
        string $id,
        Event $event,
        int $gameType,
        \DateTimeImmutable $dateTime,
        \DateTimeZone $timeZone,
        ?ParticipatingTeam $homeTeam,
        ?string $homeTeamProvisional,
        ?ParticipatingTeam $awayTeam,
        ?string $awayTeamProvisional,
        ?string $remarks,
        GameResult $result
    ) {
        Assert::uuid($id);

        $this->id    = $id;
        $this->event = $event;

        $this->setGameType($gameType)
             ->setDateTime($dateTime, $timeZone)
             ->setHomeTeam($homeTeam ?: $homeTeamProvisional)
             ->setAwayTeam($awayTeam ?: $awayTeamProvisional)
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
     * @return string
     */
    public function getGameTypeName(): string
    {
        return GameType::getGameTypeName($this->gameType, 'unknown');
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
        if (!DateTime::isTimeZoneEqual($this->dateTimeLocal, $this->getTimeZone())) {
            $this->dateTimeLocal = DateTime::reinterpret($this->dateTimeLocal, $this->getTimeZone());
        }
        return $this->dateTimeLocal;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTimeUtc(): \DateTimeImmutable
    {
        if (!DateTime::isUtc($this->dateTimeUtc)) {
            $this->dateTimeUtc = DateTime::reinterpretAsUtc($this->dateTimeUtc);
        }
        return $this->dateTimeUtc;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $timeZone
     * @return $this
     */
    private function setDateTime(\DateTimeImmutable $dateTime, \DateTimeZone $timeZone): self
    {
        $this->doSetTimeZone($timeZone);
        $this->reschedule($dateTime);
        return $this;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return $this
     */
    public function reschedule(\DateTimeImmutable $dateTime): self
    {
        $this->dateTimeLocal = DateTime::reinterpret($dateTime, $this->getTimeZone());
        $this->dateTimeUtc   = DateTime::toUtc($this->dateTimeLocal);
        return $this;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone(): \DateTimeZone
    {
        if (!$this->timeZoneInstance) {
            $this->timeZoneInstance = new \DateTimeZone($this->timeZone);
        }
        return $this->timeZoneInstance;
    }

    /**
     * @param \DateTimeZone $timeZone
     */
    private function doSetTimeZone(\DateTimeZone $timeZone): void
    {
        Assert::lengthBetween($timeZone->getName(), 1, 32);
        $this->timeZone         = $timeZone->getName();
        $this->timeZoneInstance = $timeZone;
    }

    /**
     * @param \DateTimeZone $timeZone
     * @return $this
     */
    public function updateTimeZone(\DateTimeZone $timeZone): self
    {
        $this->doSetTimeZone($timeZone);
        $this->reschedule($this->dateTimeLocal);
        return $this;
    }

    /**
     * @return string
     */
    public function getHomeTeamIdentifier(): string
    {
        return $this->homeTeam ? $this->homeTeam->getId() : $this->homeTeamProvisional;
    }

    /**
     * @return bool
     */
    public function isHomeTeamProvisional(): bool
    {
        return $this->homeTeamProvisional !== null;
    }

    /**
     * @return ParticipatingTeam|null
     */
    public function getHomeTeam(): ?ParticipatingTeam
    {
        return $this->homeTeam;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamProvisional(): ?string
    {
        return $this->homeTeamProvisional;
    }

    /**
     * @param ParticipatingTeam|string $homeTeam
     * @return $this
     */
    public function setHomeTeam($homeTeam): self
    {
        Assert::notNull($homeTeam);
        if ($homeTeam instanceof ParticipatingTeam) {
            Assert::same($this->event, $homeTeam->getEvent());
            $this->homeTeam            = $homeTeam;
            $this->homeTeamProvisional = null;
        } else {
            Assert::string($homeTeam);
            Assert::lengthBetween($homeTeam, 1, 64);
            $this->homeTeamProvisional = $homeTeam;
            $this->homeTeam            = null;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getAwayTeamIdentifier(): string
    {
        return $this->awayTeam ? $this->awayTeam->getId() : $this->awayTeamProvisional;
    }

    /**
     * @return bool
     */
    public function isAwayTeamProvisional(): bool
    {
        return $this->awayTeamProvisional !== null;
    }

    /**
     * @return ParticipatingTeam|null
     */
    public function getAwayTeam(): ?ParticipatingTeam
    {
        return $this->awayTeam;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamProvisional(): ?string
    {
        return $this->awayTeamProvisional;
    }

    /**
     * @param ParticipatingTeam|string $awayTeam
     * @return $this
     */
    public function setAwayTeam($awayTeam): self
    {
        Assert::notNull($awayTeam);
        if ($awayTeam instanceof ParticipatingTeam) {
            Assert::same($this->event, $awayTeam->getEvent());
            $this->awayTeam            = $awayTeam;
            $this->awayTeamProvisional = null;
        } else {
            Assert::string($awayTeam);
            Assert::lengthBetween($awayTeam, 1, 64);
            $this->awayTeamProvisional = $awayTeam;
            $this->awayTeam            = null;
        }
        return $this;
    }

    /**
     * @param string $glue
     * @return string
     */
    public function getFixture(string $glue = ' vs. '): string
    {
        $home = $this->isHomeTeamProvisional() ? $this->homeTeamProvisional : $this->homeTeam->getName();
        $away = $this->isAwayTeamProvisional() ? $this->awayTeamProvisional : $this->awayTeam->getName();
        return $home . $glue . $away;
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
