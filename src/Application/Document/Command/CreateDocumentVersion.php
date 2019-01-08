<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateDocumentVersion
 *
 * @package App\Application\Document\Command
 */
class CreateDocumentVersion
{
    use UuidAware, MutableDocumentVersion, DocumentVersionProperties;

    /**
     * @param string $documentId
     * @return self
     */
    public static function create(string $documentId): self
    {
        return new self(self::createUuid(), $documentId);
    }

    /**
     * @param string $id
     * @param string $documentId
     */
    private function __construct(string $id, string $documentId)
    {
        $this->id         = $id;
        $this->documentId = $documentId;
    }
}
