<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:44
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Document\Document;

/**
 * Class DeleteDocument
 *
 * @package App\Application\Document\Command
 */
class DeleteDocument
{
    use IdAware;

    /**
     * @param Document $document
     * @return self
     */
    public static function delete(Document $document): self
    {
        return new self($document->getId());
    }
}
