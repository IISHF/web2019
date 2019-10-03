<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-12
 * Time: 10:48
 */

namespace App\Domain\Model\Common;

use BadMethodCallException;

/**
 * Trait AssociationOne
 *
 * @package App\Domain\Model\Common
 */
trait AssociationOne
{
    /**
     * @param object|null $relatedEntity
     * @param string      $errorMessage
     * @return object
     */
    protected function getRelatedEntity(?object $relatedEntity, string $errorMessage): object
    {
        if (!$relatedEntity) {
            throw new BadMethodCallException($errorMessage);
        }
        return $relatedEntity;
    }

    /**
     * @param object|null   $currentEntity
     * @param object|null   $newEntity
     * @param callable|null $setter
     * @param callable|null $unSetter
     * @return $this
     */
    protected function setRelatedEntity(
        ?object &$currentEntity,
        ?object $newEntity,
        ?callable $setter = null,
        ?callable $unSetter = null
    ): self {
        if ($newEntity === $currentEntity) {
            return $this;
        }

        if ($currentEntity) {
            $previous      = $currentEntity;
            $currentEntity = null;
            if ($unSetter) {
                $unSetter($previous, $this);
            }
        }
        if ($newEntity) {
            $currentEntity = $newEntity;
            if ($setter) {
                $setter($newEntity, $this);
            }
        }
        return $this;
    }
}
