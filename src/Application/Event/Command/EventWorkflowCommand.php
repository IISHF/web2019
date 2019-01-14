<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:39
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\Event;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EventWorkflowCommand
 *
 * @package App\Application\Event\Command
 */
abstract class EventWorkflowCommand
{
    use UuidAware;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $transition;

    /**
     * @param Event  $event
     * @param string $transition
     * @return self
     */
    public static function create(Event $event, string $transition): self
    {
        switch ($transition) {
            case AwardTitleEvent::TRANSITION:
                return new AwardTitleEvent($event->getId());
            case SanctionEvent::TRANSITION:
                return new SanctionEvent($event->getId());
            default:
                return new TransitionEvent($event->getId(), $transition);
        }
    }

    /**
     * @param string $id
     * @param string $transition
     */
    protected function __construct(string $id, string $transition)
    {
        $this->id         = $id;
        $this->transition = $transition;
    }

    /**
     * @return string
     */
    public function getTransition(): string
    {
        return $this->transition;
    }
}