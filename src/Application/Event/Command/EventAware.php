<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:32
 */

namespace App\Application\Event\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait EventAware
 *
 * @package App\Application\Event\Command
 */
trait EventAware
{
    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $eventId;

    /**
     * @return string
     */
    public function getEventId(): string
    {
        return $this->eventId;
    }
}
