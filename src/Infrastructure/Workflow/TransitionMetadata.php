<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 08:36
 */

namespace App\Infrastructure\Workflow;

use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Transition;

/**
 * Class TransitionMetadata
 *
 * @package App\Infrastructure\Workflow
 */
class TransitionMetadata
{
    /**
     * @var Transition
     */
    private $transition;

    /**
     * @var MetadataStoreInterface
     */
    private $metadataStore;

    /**
     * @param Transition             $transition
     * @param MetadataStoreInterface $metadataStore
     */
    public function __construct(Transition $transition, MetadataStoreInterface $metadataStore)
    {
        $this->transition    = $transition;
        $this->metadataStore = $metadataStore;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->transition->getName();
    }

    /**
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->metadataStore->getMetadata($key, $this->transition) ?? $default;
    }

    /**
     * @return string|null
     */
    public function getFormType(): ?string
    {
        return $this->getMetadata('form_type');
    }
}
