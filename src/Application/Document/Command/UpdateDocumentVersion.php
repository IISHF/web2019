<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:47
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Document\DocumentVersion;

/**
 * Class UpdateDocumentVersion
 *
 * @package App\Application\Document\Command
 */
class UpdateDocumentVersion
{
    use UuidAware, DocumentVersionProperties;

    /**
     * @param DocumentVersion $documentVersion
     * @return self
     */
    public static function update(DocumentVersion $documentVersion): self
    {
        return new self(
            $documentVersion->getId(),
            $documentVersion->getVersion(),
            $documentVersion->getValidFrom(),
            $documentVersion->getValidUntil()
        );
    }

    /**
     * @param string                  $id
     * @param string                  $version
     * @param \DateTimeImmutable|null $validFrom
     * @param \DateTimeImmutable|null $validUntil
     */
    private function __construct(
        string $id,
        string $version,
        ?\DateTimeImmutable $validFrom,
        ?\DateTimeImmutable $validUntil
    ) {
        $this->id         = $id;
        $this->version    = $version;
        $this->validFrom  = $validFrom;
        $this->validUntil = $validUntil;
    }
}
