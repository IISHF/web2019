<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:19
 */

namespace App\Domain\Model\Common;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait SoftDeleteableEntity
 *
 * @package App\Domain\Model\Common
 */
trait SoftDeleteableEntity
{
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $deletedAt;

    /**
     * @param DateTimeImmutable $deletedAt
     * @return $this
     */
    public function delete(DateTimeImmutable $deletedAt): self
    {
        $dateTime = new DateTime(null, $deletedAt->getTimezone());
        $dateTime->setTimestamp($deletedAt->getTimestamp());
        $this->deletedAt = $dateTime;
        return $this;
    }

    /**
     * @return SoftDeleteableEntity
     */
    public function undelete(): self
    {
        $this->deletedAt = null;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        if ($this->deletedAt) {
            return DateTimeImmutable::createFromMutable($this->deletedAt);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
