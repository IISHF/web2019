<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:29
 */

namespace App\Application\Event\Game\Command;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ScheduleProperties
 *
 * @package App\Application\Event\Game\Command
 */
trait ScheduleProperties
{

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     *
     * @var DateTimeImmutable
     */
    private $dateTime;

    /**
     * @return DateTimeImmutable
     */
    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return $this
     */
    public function setDateTime(DateTimeImmutable $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }
}
