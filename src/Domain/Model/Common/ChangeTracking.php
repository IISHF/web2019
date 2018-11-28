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
     * @ORM\Column(type="string", length=128)
     * @Gedmo\Blameable(on="create")
     *
     * @var string
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
     * @ORM\Column(type="string", length=128)
     * @Gedmo\Blameable(on="update")
     *
     * @var string
     */
    private $updatedBy;

    /**
     * @param string $user
     * @return $this
     */
    private function initChangeTracking(string $user = 'system'): self
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = $user;
        $this->updatedAt = new \DateTimeImmutable('now');
        $this->updatedBy = $user;
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
     * @return string
     */
    public function getCreatedBy(): string
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
     * @return string
     */
    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }
}
