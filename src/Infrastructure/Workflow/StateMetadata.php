<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 08:55
 */

namespace App\Infrastructure\Workflow;

use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;

/**
 * Class StateMetadata
 *
 * @package App\Infrastructure\Workflow
 */
class StateMetadata
{
    /**
     * @var string
     */
    private $state;

    /**
     * @var MetadataStoreInterface
     */
    private $metadataStore;

    /**
     * @param string                 $state
     * @param MetadataStoreInterface $metadataStore
     */
    public function __construct(string $state, MetadataStoreInterface $metadataStore)
    {
        $this->state         = $state;
        $this->metadataStore = $metadataStore;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->state;
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getMetadata(string $key, $default = null)
    {
        return $this->metadataStore->getMetadata($key, $this->state) ?? $default;
    }
}
