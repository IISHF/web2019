<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:39
 */

namespace App\Application\File\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\File\File;

/**
 * Class RemoveFile
 *
 * @package App\Application\File\Command
 */
class RemoveFile
{
    use UuidAware;

    /**
     * @param File $file
     * @return self
     */
    public static function remove(File $file): self
    {
        return new self($file->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
