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
 * Class UpdateDocument
 *
 * @package App\Application\Document\Command
 */
class UpdateDocument
{
    use UuidAware, DocumentProperties;

    /**
     * @param Document $document
     * @return self
     */
    public static function update(Document $document): self
    {
        return new self(
            $document->getId(),
            $document->getTitle(),
            $document->getTags()
        );
    }

    /**
     * @param string $id
     * @param string $title
     * @param array  $tags
     */
    private function __construct(string $id, string $title, array $tags)
    {
        $this->id    = $id;
        $this->title = $title;
        $this->tags  = $tags;
    }
}
