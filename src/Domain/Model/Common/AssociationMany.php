<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:48
 */

namespace App\Domain\Model\Common;

use Doctrine\Common\Collections\Collection;

/**
 * Trait AssociationMany
 *
 * @package App\Domain\Model\Common
 */
trait AssociationMany
{
    /**
     * @param Collection $relatedEntities
     * @return object[]
     */
    protected function getRelatedEntities(Collection $relatedEntities): array
    {
        return $relatedEntities->toArray();
    }

    /**
     * @param Collection    $relatedEntities
     * @param object        $newEntity
     * @param callable|null $adder
     * @return $this
     */
    protected function addRelatedEntity(
        Collection $relatedEntities,
        object $newEntity,
        ?callable $adder = null
    ): self {
        if ($relatedEntities->contains($newEntity)) {
            return $this;
        }
        $relatedEntities->add($newEntity);
        if ($adder) {
            $adder($this, $newEntity);
        }
        return $this;
    }

    /**
     * @param Collection    $relatedEntities
     * @param object        $relatedEntity
     * @param callable|null $remover
     * @return $this
     */
    protected function removeRelatedEntity(
        Collection $relatedEntities,
        object $relatedEntity,
        ?callable $remover = null
    ): self {
        if (!$relatedEntities->contains($relatedEntity)) {
            return $this;
        }
        $relatedEntities->removeElement($relatedEntity);
        if ($remover) {
            $remover($this, $relatedEntity);
        }
        return $this;
    }
}
