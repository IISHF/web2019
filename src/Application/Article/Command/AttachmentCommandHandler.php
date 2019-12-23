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
use SplFileInfo;

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
     * @param ArticleRepository $articleRepository
     * @param FileFactory       $fileFactory
     */
    public function __construct(ArticleRepository $articleRepository, FileFactory $fileFactory)
    {
        parent::__construct($articleRepository);
        $this->fileFactory = $fileFactory;
    }

    /**
     * @param string       $origin
     * @param SplFileInfo $file
     * @return File
     */
    protected function createFile(string $origin, SplFileInfo $file): File
    {
        return $this->fileFactory->createFile($file, $origin);
    }
}
