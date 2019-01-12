<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:13
 */

namespace App\Domain\Model\Common;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait CreateTracking
 *
 * @package App\Domain\Model\Common
 */
trait CreateTracking
{
    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @ORM\Column(name="created_by", type="string", length=128, nullable=true)
     * @Gedmo\Blameable(on="create")
     *
     * @var string|null
     */
    private $createdBy;

    /**
     * @return $this
     */
    protected function initCreateTracking(): self
    {
        $this->createdAt = new \DateTimeImmutable('now');
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }
}
