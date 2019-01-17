<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 07:54
 */

namespace App\Infrastructure\Workflow;

use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

/**
 * Class WorkflowMetadata
 *
 * @package App\Infrastructure\Workflow
 */
class WorkflowMetadata
{
    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * @var object
     */
    private $subject;

    /**
     * @param Registry    $registry
     * @param object      $subject
     * @param string|null $name
     * @return self
     */
    public static function find(Registry $registry, object $subject, ?string $name = null): self
    {
        return new self($registry->get($subject, $name), $subject);
    }

    /**
     * @param Workflow $workflow
     * @param object   $subject
     */
    private function __construct(Workflow $workflow, object $subject)
    {
        $this->workflow = $workflow;
        $this->subject  = $subject;
    }

    /**
     * @return Workflow
     */
    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    /**
     * @return object
     */
    public function getSubject(): object
    {
        return $this->subject;
    }

    /**
     * @return TransitionMetadata[]
     */
    public function getEnabledTransitions(): iterable
    {
        foreach ($this->workflow->getEnabledTransitions($this->subject) as $t) {
            yield new TransitionMetadata($t, $this->getMetadataStore());
        }
    }

    /**
     * @param string $transition
     * @return TransitionMetadata|null
     */
    public function findEnabledTransition(string $transition): ?TransitionMetadata
    {
        foreach ($this->getEnabledTransitions() as $t) {
            if ($t->getName() === $transition) {
                return $t;
            }
        }
        return null;
    }

    /**
     * @return StateMetadata[]
     */
    public function getCurrentStates(): iterable
    {
        $marking = $this->workflow->getMarking($this->subject);
        foreach ($marking->getPlaces() as $p) {
            yield new StateMetadata($p, $this->getMetadataStore());
        }
    }

    /**
     * @return StateMetadata
     */
    public function getCurrentState(): StateMetadata
    {
        $marking = $this->workflow->getMarking($this->subject);
        $places  = $marking->getPlaces();
        return new StateMetadata($places[0], $this->getMetadataStore());
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->getMetadataStore()->getMetadata($key) ?? $default;
    }

    /**
     * @return MetadataStoreInterface
     */
    private function getMetadataStore(): MetadataStoreInterface
    {
        return $this->workflow->getMetadataStore();
    }
}
