<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 18:01
 */

namespace App\Controller;

use App\Application\File\Command\AddFile;
use App\Application\File\Command\RemoveFile;
use App\Domain\Model\File\FileRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 *
 * @package App\Controller
 *
 * @Route("/files")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/upload", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param FileRepository      $fileRepository
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function upload(Request $request, FileRepository $fileRepository, MessageBusInterface $commandBus): Response
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException();
        }

        $originalName = $uploadedFile->getClientOriginalName();

        $addFile = AddFile::add($uploadedFile->move(sys_get_temp_dir()), null, $originalName);
        $commandBus->dispatch($addFile);

        $file = $fileRepository->findById($addFile->getId());
        if (!$file) {
            throw $this->createNotFoundException();
        }

        $url = $this->generateUrl('app_file_download', ['name' => $file->getName()]);

        return JsonResponse::create(
            [
                'filename'    => $originalName,
                'contentType' => $file->getMimeType(),
                'filesize'    => $file->getSize(),
                'url'         => $url,
                'href'        => $url,
            ]
        );
    }


    /**
     * @Route(
     *     "/{name}",
     *     methods={"DELETE"},
     *     requirements={"name": "%routing.uuid%\.[0-9a-z]{1,16}"}
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param string              $name
     * @param FileRepository      $fileRepository
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(string $name, FileRepository $fileRepository, MessageBusInterface $commandBus): Response
    {
        $file = $fileRepository->findByNameWithBinary($name);
        if (!$file) {
            throw $this->createNotFoundException();
        }

        $removeFile = RemoveFile::remove($file);
        $commandBus->dispatch($removeFile);

        return Response::create('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/{name}",
     *     methods={"GET"},
     *     requirements={"name": "%routing.uuid%\.[0-9a-z]{1,16}"}
     * )
     *
     * @param string         $name
     * @param Request        $request
     * @param FileRepository $fileRepository
     * @return Response
     */
    public function download(string $name, Request $request, FileRepository $fileRepository): Response
    {
        $file = $fileRepository->findByNameWithBinary($name);
        if (!$file) {
            throw $this->createNotFoundException();
        }

        $date = $file->getCreatedAt();
        $etag = $file->getEtag();

        $response = Response::create()
                            ->setEtag($etag)
                            ->setLastModified($date)
                            ->setPublic();
        if ($response->isNotModified($request)) {
            return $response;
        }

        return BinaryFileResponse::create($file->writeTo(null))
                                 ->setContentDisposition(HeaderUtils::DISPOSITION_INLINE, $file->getClientName())
                                 ->setEtag($etag)
                                 ->setLastModified($date)
                                 ->setPublic()
                                 ->deleteFileAfterSend();
    }
}
