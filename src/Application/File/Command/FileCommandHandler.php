<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:41
 */

namespace App\Application\File\Command;

use App\Application\File\FileManager;
use App\Domain\Model\File\File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class FileCommandHandler
 *
 * @package App\Application\File\Command
 */
abstract class FileCommandHandler implements MessageHandlerInterface
{
    /**
     * @var FileManager
     */
    protected $fileManager;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param string $id
     * @return File
     */
    protected function getFile(string $id): File
    {
        $file = $this->fileManager->findById($id);
        if (!$file) {
            throw new \OutOfBoundsException('No file found for id ' . $id);
        }
        return $file;
    }
}
