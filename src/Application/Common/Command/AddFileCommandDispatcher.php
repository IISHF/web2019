<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Common\Command;

use App\Application\File\Command\AddFile;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileRepository;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile as HttpUploadedFile;

/**
 * Trait AddFileCommandDispatcher
 *
 * @package App\Application\Common\Command
 */
trait AddFileCommandDispatcher
{
    use RemoveFileCommandDispatcher;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param SplFileInfo $newFile
     * @param string      $origin
     * @return File
     */
    private function dispatchAddFile(SplFileInfo $newFile, string $origin): File
    {
        $originalName = null;
        if ($newFile instanceof HttpUploadedFile) {
            $originalName = $newFile->getClientOriginalName();
            $newFile      = $newFile->move(sys_get_temp_dir());
        }

        $addFile = AddFile::add($newFile, $origin, $originalName);
        $this->dispatchCommand($addFile);

        $file = $this->fileRepository->findById($addFile->getId());
        if (!$file) {
            throw new RuntimeException('Something went wrong when saving the file');
        }
        return $file;
    }
}
