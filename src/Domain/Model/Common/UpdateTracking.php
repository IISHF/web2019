<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:15
 */

namespace App\Domain\Model\Common;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait UpdateTracking
 *
 * @package App\Domain\Model\Common
 */
trait UpdateTracking
{
    /**
     * @ORM\Column(name="updated_at", type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     *
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="updated_by", type="string", length=128, nullable=true)
     * @Gedmo\Blameable(on="update")
     *
     * @var string|null
     */
    private $updatedBy;

    /**
     * @return $this
     */
    protected function initUpdateTracking(): self
    {
        $this->updatedAt = new DateTimeImmutable('now');
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }
}
