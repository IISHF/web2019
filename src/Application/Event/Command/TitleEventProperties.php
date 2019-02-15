<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:43
 */

namespace App\Application\Event\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait TitleEventProperties
 *
 * @package App\Application\Event\Command
 */
trait TitleEventProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=1, max=31)
     * @Assert\NotNull()
     *
     * @var int
     */
    private $plannedLength = 2;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=65535)
     *
     * @var string|null
     */
    private $description;

    /**
     * @return int
     */
    public function getPlannedLength(): int
    {
        return $this->plannedLength;
    }

    /**
     * @param int $plannedLength
     * @return $this
     */
    public function setPlannedLength(int $plannedLength): self
    {
        $this->plannedLength = $plannedLength;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
