<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Common\Command;

use App\Application\File\Command\RemoveFile;
use App\Domain\Model\File\File;

/**
 * Trait RemoveFileCommandDispatcher
 *
 * @package App\Application\Common\Command
 */
trait RemoveFileCommandDispatcher
{
    use CommandDispatcher;

    /**
     * @param File|null $file
     * @return bool
     */
    private function dispatchRemoveFileIfExists(?File $file): bool
    {
        if ($file !== null) {
            $removeFile = RemoveFile::remove($file);
            $this->dispatchCommand($removeFile);
            return true;
        }
        return false;
    }
}
