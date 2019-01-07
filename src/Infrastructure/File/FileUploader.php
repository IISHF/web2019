<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-05
 * Time: 11:32
 */

namespace App\Infrastructure\File;

use App\Application\File\Command\AddFile;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class FileUploader
 *
 * @package App\Infrastructure\File
 */
class FileUploader
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @param FileRepository      $fileRepository
     * @param MessageBusInterface $commandBus
     */
    public function __construct(FileRepository $fileRepository, MessageBusInterface $commandBus)
    {
        $this->fileRepository = $fileRepository;
        $this->commandBus     = $commandBus;
    }

    /**
     * @param Request     $request
     * @param string|null $originalName
     * @param string      $key
     * @return File
     */
    public function uploadFile(Request $request, ?string &$originalName = null, string $key = 'file'): File
    {
        $uploadedFile = $request->files->get($key);

        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException();
        }

        $originalName = $uploadedFile->getClientOriginalName();

        $addFile = AddFile::add($uploadedFile->move(sys_get_temp_dir()), 'com.iishf.upload', $originalName);
        $this->commandBus->dispatch($addFile);

        $file = $this->fileRepository->findById($addFile->getId());
        if (!$file) {
            throw new NotFoundHttpException('Not Found');
        }

        return $file;
    }
}
