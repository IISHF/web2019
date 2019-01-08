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
    use UuidAware, MutableDocument, DocumentProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
