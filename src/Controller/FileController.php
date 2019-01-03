<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 18:01
 */

namespace App\Controller;

use App\Application\File\FileManager;
use App\Domain\Model\File\FileRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @param Request     $request
     * @param FileManager $fileManager
     * @return Response
     */
    public function upload(Request $request, FileManager $fileManager): Response
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile instanceof UploadedFile) {
            throw new BadRequestHttpException();
        }

        $originalName = $uploadedFile->getClientOriginalName();
        $extension    = $uploadedFile->guessExtension() ?? 'bin';

        $file = $fileManager->createFile(
            null,
            $originalName,
            $uploadedFile->move(sys_get_temp_dir())
        );
        $fileManager->save($file);

        $url = $this->generateUrl('app_file_download', ['id' => $file->getId(), 'ext' => $extension]);

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
     *     "/{id}.{ext<[0-9a-z]{1,}>}",
     *     methods={"GET"},
     *     requirements={"id": "%routing.uuid%"}
     * )
     *
     * @param string         $id
     * @param FileRepository $fileRepository
     * @return Response
     */
    public function download(string $id, FileRepository $fileRepository): Response
    {
        $file = $fileRepository->findByIdWithBinary($id);
        if (!$file) {
            throw $this->createNotFoundException();
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'image_');
        file_put_contents($tempFile, $file->getBinary()->getData());

        return BinaryFileResponse::create($tempFile, Response::HTTP_OK, [], true, 'inline');

    }

}
