<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:57
 */

namespace App\Application\Document\Command;

/**
 * Trait MutableDocumentVersion
 *
 * @package App\Application\Document\Command
 */
trait MutableDocumentVersion
{
    /**
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $validFrom
     * @return $this
     */
    public function setValidFrom(?\DateTimeImmutable $validFrom): self
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $validUntil
     * @return $this
     */
    public function setValidUntil(?\DateTimeImmutable $validUntil): self
    {
        $this->validUntil = $validUntil;
        return $this;
    }
}
