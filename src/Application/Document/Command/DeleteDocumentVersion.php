<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Document\DocumentVersion;

/**
 * Class DeleteDocumentVersion
 *
 * @package App\Application\Document\Command
 */
class DeleteDocumentVersion
{
    use IdAware;

    /**
     * @param DocumentVersion $documentVersion
     * @return self
     */
    public static function delete(DocumentVersion $documentVersion): self
    {
        return new self($documentVersion->getId());
    }
}
