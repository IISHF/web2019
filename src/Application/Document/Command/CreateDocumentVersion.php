<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateDocumentVersion
 *
 * @package App\Application\Document\Command
 */
class CreateDocumentVersion
{
    use UuidAware, DocumentVersionProperties, MutableDocumentVersion, DocumentVersionFile;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $documentId;

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

    /**
     * @return string
     */
    public function getDocumentId(): string
    {
        return $this->documentId;
    }
}
