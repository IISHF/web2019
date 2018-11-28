<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 08:58
 */

namespace App\Domain\Model\Common;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait ChangeTracking
 *
 * @package App\Domain\Model\Common
 */
trait ChangeTracking
{
    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Gedmo\Blameable(on="create")
     *
     * @var string|null
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Gedmo\Blameable(on="update")
     *
     * @var string|null
     */
    private $updatedBy;

    /**
     * @return $this
     */
    private function initChangeTracking(): self
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->updatedAt = new \DateTimeImmutable('now');
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

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
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
