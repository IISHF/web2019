<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:43
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateDocument
 *
 * @package App\Application\Document\Command
 */
class CreateDocument
{
    use UuidAware,
        DocumentProperties, MutableDocument,
        DocumentVersionProperties, MutableDocumentVersion,
        DocumentVersionFile;
    /**
     * @var string
     */
    private $versionId;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid(), self::createUuid());
    }

    /**
     * @param string $id
     * @param string $versionId
     */
    private function __construct(string $id, string $versionId)
    {
        $this->id        = $id;
        $this->versionId = $versionId;
    }

    /**
     * @return string
     */
    public function getVersionId(): string
    {
        return $this->versionId;
    }
}
