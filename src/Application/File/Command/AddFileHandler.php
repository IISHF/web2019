<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:40
 */

namespace App\Application\File\Command;

/**
 * Class AddFileHandler
 *
 * @package App\Application\File\Command
 */
class AddFileHandler extends FileCommandHandler
{
    /**
     * @param AddFile $command
     */
    public function __invoke(AddFile $command): void
    {
        $file = $this->fileManager->createFileWithId(
            $command->getId(),
            $command->getFile(),
            $command->getOrigin(),
            $command->getOriginalName()
        );
        $this->fileManager->save($file);
    }
}
