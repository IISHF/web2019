<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:39
 */

namespace App\Application\Event\Command\Workflow;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Event\Event;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EventWorkflowCommand
 *
 * @package App\Application\Event\Command\Workflow
 */
abstract class EventWorkflowCommand
{
    use IdAware;

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
            case AnnounceTitleEvent::TRANSITION:
                return new AnnounceTitleEvent($event->getId());
            case WithholdTitleEvent::TRANSITION:
                return new WithholdTitleEvent($event->getId());
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
