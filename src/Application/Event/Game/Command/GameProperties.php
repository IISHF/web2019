<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:00
 */

namespace App\Application\Event\Game\Command;

use App\Application\Event\Game\Validator\GameType as ValidGameType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait GameProperties
 *
 * @package App\Application\Event\Game\Command
 */
trait GameProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @ValidGameType()
     *
     * @var int
     */
    private $gameType;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     * @var string|null
     */
    private $remarks;

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
        $this->gameType = $gameType;
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
        $this->remarks = $remarks;
        return $this;
    }
}
