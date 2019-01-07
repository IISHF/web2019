<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-05
 * Time: 11:32
 */

namespace App\Infrastructure\File;

use App\Application\File\Command\AddFile;
use App\Domain\Model\File\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile as HttpUploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param FileRepository        $fileRepository
     * @param MessageBusInterface   $commandBus
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        FileRepository $fileRepository,
        MessageBusInterface $commandBus,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->fileRepository = $fileRepository;
        $this->commandBus     = $commandBus;
        $this->urlGenerator   = $urlGenerator;
    }

    /**
     * @param Request $request
     * @param string  $origin
     * @param string  $key
     * @return UploadedFile
     */
    public function uploadFile(Request $request, string $origin, string $key = 'file'): UploadedFile
    {
        $uploadedFile = $request->files->get($key);
        if (!$uploadedFile instanceof HttpUploadedFile) {
            throw new BadRequestHttpException();
        }

        $addFile = AddFile::add(
            $uploadedFile->move(sys_get_temp_dir()),
            $origin,
            $uploadedFile->getClientOriginalName()
        );
        $this->commandBus->dispatch($addFile);

        $file = $this->fileRepository->findById($addFile->getId());
        if (!$file) {
            throw new NotFoundHttpException('Not Found');
        }

        return new UploadedFile(
            $file,
            $this->urlGenerator->generate(
                'app_file_download',
                ['name' => $file->getName()],
                UrlGeneratorInterface::ABSOLUTE_PATH

            ),
            $this->urlGenerator->generate(
                'app_file_download',
                ['name' => $file->getName()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }
}
