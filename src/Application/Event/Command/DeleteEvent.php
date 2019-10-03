<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:12
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Event\Event;

/**
 * Class DeleteEvent
 *
 * @package App\Application\Event\Command
 */
class DeleteEvent
{
    use IdAware;

    /**
     * @param Event $event
     * @return self
     */
    public static function delete(Event $event): self
    {
        return new self($event->getId());
    }
}
