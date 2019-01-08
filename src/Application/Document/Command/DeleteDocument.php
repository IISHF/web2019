<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:44
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Document\Document;

/**
 * Class DeleteDocument
 *
 * @package App\Application\Document\Command
 */
class DeleteDocument
{
    use UuidAware;

    /**
     * @param Document $document
     * @return self
     */
    public static function delete(Document $document): self
    {
        return new self($document->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
