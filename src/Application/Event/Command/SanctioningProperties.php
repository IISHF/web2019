<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 07:29
 */

namespace App\Application\Event\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait SanctioningProperties
 *
 * @package App\Application\Event\Command
 */
trait SanctioningProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $sanctionNumber = '';

    /**
     * @return string
     */
    public function getSanctionNumber(): string
    {
        return $this->sanctionNumber;
    }

    /**
     * @param string $sanctionNumber
     * @return $this
     */
    public function setSanctionNumber(string $sanctionNumber): self
    {
        $this->sanctionNumber = $sanctionNumber;
        return $this;
    }

}
