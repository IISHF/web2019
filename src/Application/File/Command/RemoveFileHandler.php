<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:40
 */

namespace App\Application\File\Command;

/**
 * Class RemoveFileHandler
 *
 * @package App\Application\File\Command
 */
class RemoveFileHandler extends FileCommandHandler
{
    /**
     * @param RemoveFile $command
     */
    public function __invoke(RemoveFile $command): void
    {
        $file = $this->getFile($command->getId());
        $this->fileManager->delete($file);
    }
}
