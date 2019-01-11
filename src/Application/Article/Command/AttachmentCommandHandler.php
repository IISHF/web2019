<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:21
 */

namespace App\Application\Article\Command;

use App\Application\File\FileFactory;
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
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @param ArticleRepository $repository
     * @param FileFactory       $fileFactory
     */
    public function __construct(ArticleRepository $repository, FileFactory $fileFactory)
    {
        parent::__construct($repository);
        $this->fileFactory = $fileFactory;
    }

    /**
     * @param string       $origin
     * @param \SplFileInfo $file
     * @return File
     */
    protected function createFile(string $origin, \SplFileInfo $file): File
    {
        return $this->fileFactory->createFile($file, $origin);
    }
}
