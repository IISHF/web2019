<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:21
 */

namespace App\Application\Article\Command;

use App\Application\File\FileManager;
use App\Domain\Model\Article\ArticleRepository;
use App\Domain\Model\File\File;

/**
 * Class AttachmentCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class AttachmentCommandHandler extends ArticleCommandHandler
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @param ArticleRepository $repository
     * @param FileManager       $fileManager
     */
    public function __construct(ArticleRepository $repository, FileManager $fileManager)
    {
        parent::__construct($repository);
        $this->fileManager = $fileManager;
    }

    /**
     * @param string       $reference
     * @param \SplFileInfo $file
     * @return File
     */
    protected function createFile(string $reference, \SplFileInfo $file): File
    {
        return $this->fileManager->createFile($file, $reference);
    }
}
