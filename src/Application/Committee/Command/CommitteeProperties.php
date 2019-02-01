<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:55
 */

namespace App\Application\Committee\Command;

use App\Application\Committee\Validator\UniqueCommitteeTitle;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait CommitteeProperties
 *
 * @package App\Application\Committee\Command
 */
trait CommitteeProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     * @UniqueCommitteeTitle()
     *
     * @var string
     */
    private $title = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=65535)
     *
     * @var string|null
     */
    private $description;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
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
