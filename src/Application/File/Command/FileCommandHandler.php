<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 11:41
 */

namespace App\Application\File\Command;

use App\Application\File\FileFactory;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileRepository;
use OutOfBoundsException;
use SplFileInfo;

/**
 * Class FileCommandHandler
 *
 * @package App\Application\File\Command
 */
abstract class FileCommandHandler
{
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var FileRepository
     */
    protected $fileRepository;

    /**
     * @param FileFactory    $fileFactory
     * @param FileRepository $fileRepository
     */
    public function __construct(FileFactory $fileFactory, FileRepository $fileRepository)
    {
        $this->fileFactory    = $fileFactory;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param string $id
     * @return File
     */
    protected function getFile(string $id): File
    {
        $file = $this->fileRepository->findById($id);
        if (!$file) {
            throw new OutOfBoundsException('No file found for id ' . $id);
        }
        return $file;
    }

    /**
     * @param string       $id
     * @param SplFileInfo $file
     * @param string       $origin
     * @param string|null  $originalName
     * @return File
     */
    protected function createFile(string $id, SplFileInfo $file, string $origin, ?string $originalName): File
    {
        return $this->fileFactory->createFileWithId($id, $file, $origin, $originalName);
    }
}
