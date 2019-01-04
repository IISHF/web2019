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
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

        $width       = $request->query->getInt('w');
        $height      = $request->query->getInt('h');
        $resizeImage = false;
        $pdfPreview  = false;
        if (($width !== 0 || $height !== 0)
            && strpos($file->getMimeType(), 'image/') === 0
        ) {
            $date        = null;
            $etag        = sha1($etag . '/w=' . $width . '/h=' . $height);
            $resizeImage = true;
        } elseif (($width !== 0 || $height !== 0)
            && ($file->getMimeType() === 'application/pdf' || $file->getMimeType() === 'application/x-pdf')
        ) {
            $date       = null;
            $etag       = sha1($etag . '/pdf-preview/w=' . $width . '/h=' . $height);
            $pdfPreview = true;
        }

        $response = Response::create()
                            ->setEtag($etag)
                            ->setLastModified($date)
                            ->setPublic();
        if ($response->isNotModified($request)) {
            return $response;
        }

        $contentDisposition = HeaderUtils::DISPOSITION_INLINE;
        if ($request->query->has('download')) {
            $contentDisposition = HeaderUtils::DISPOSITION_ATTACHMENT;
        }

        if ($resizeImage) {
            return $this->createResizedImageResponse($etag, $date, $file, $contentDisposition, $width, $height);
        }

        if ($pdfPreview) {
            return $this->createPreviewPdfResponse($etag, $date, $file, $contentDisposition, $width, $height);
        }

        return BinaryFileResponse::create($file->writeTo(null))
                                 ->setContentDisposition(
                                     $contentDisposition,
                                     $file->getClientName(),
                                     $file->getSafeClientName()
                                 )
                                 ->setEtag($etag)
                                 ->setLastModified($date)
                                 ->setPublic()
                                 ->deleteFileAfterSend();
    }

    /**
     * @param string                  $etag
     * @param \DateTimeImmutable|null $date
     * @param File                    $file
     * @param string                  $contentDisposition
     * @param string|null             $mimeType
     * @param string|null             $addExtension
     * @return StreamedResponse
     */
    private function prepareStreamedResponse(
        string $etag,
        ?\DateTimeImmutable $date,
        File $file,
        string $contentDisposition,
        ?string $mimeType = null,
        ?string $addExtension = null
    ): StreamedResponse {
        $filename     = $file->getClientName();
        $safeFilename = $file->getSafeClientName();
        if ($addExtension !== null) {
            $filename     .= '.' . $addExtension;
            $safeFilename .= '.' . $addExtension;
        }

        $response = StreamedResponse::create()
                                    ->setEtag($etag)
                                    ->setLastModified($date)
                                    ->setPublic();
        $headers  = $response->headers;
        $headers->set('Content-Type', $mimeType ?? $file->getMimeType());
        $headers->set(
            'Content-Disposition',
            $headers->makeDisposition($contentDisposition, $filename, $safeFilename)
        );
        return $response;
    }

    /**
     * @param File $file
     * @return \Imagick
     */
    private function createImage(File $file): \Imagick
    {
        $imFilename = $file->writeTo(null);
        $im         = new \Imagick($imFilename);
        unlink($imFilename);
        return $im;
    }

    /**
     * @param \Imagick $im
     * @param int      $width
     * @param int      $height
     * @return \Imagick
     */
    private function resizeImageAndCrop(\Imagick $im, int $width, int $height): \Imagick
    {
        if ($width > 0 && $height > 0) {
            $ratio    = $width / $height;
            $imWidth  = $im->getImageWidth();
            $imHeight = $im->getImageHeight();
            $imRatio  = $imWidth / $imHeight;
            if ($ratio > $imRatio) {
                $newWidth  = $width;
                $newHeight = $width / $imWidth * $imHeight;
                $cropX     = 0;
                $cropY     = (int)(($newHeight - $height) / 2);
            } else {
                $newWidth  = $height / $imHeight * $imWidth;
                $newHeight = $height;
                $cropX     = (int)(($newWidth - $width) / 2);
                $cropY     = 0;
            }
            $im->resizeImage($newWidth, $newHeight, \Imagick::FILTER_LANCZOS, 1.0, true);
            $im->cropImage($width, $height, $cropX, $cropY);
        } else {
            $im->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1.0, true);
        }
        return $im;
    }

    /**
     * @param File          $file
     * @param int           $width
     * @param int           $height
     * @param callable|null $fn
     * @return callable
     */
    private function createResponseCallback(File $file, int $width, int $height, ?callable $fn = null): callable
    {
        return function () use ($file, $width, $height, $fn) {
            $im = $this->createImage($file);
            if ($fn) {
                $im = $fn($im);
            }
            echo $this->resizeImageAndCrop($im, $width, $height)
                      ->getImageBlob();
            $im->destroy();
            unset($im);
        };
    }

    /**
     * @param string                  $etag
     * @param \DateTimeImmutable|null $date
     * @param File                    $file
     * @param string                  $contentDisposition
     * @param int                     $width
     * @param int                     $height
     * @return StreamedResponse
     */
    private function createResizedImageResponse(
        string $etag,
        ?\DateTimeImmutable $date,
        File $file,
        string $contentDisposition,
        int $width,
        int $height
    ): StreamedResponse {
        $response = $this->prepareStreamedResponse($etag, $date, $file, $contentDisposition);
        return $response->setCallback($this->createResponseCallback($file, $width, $height));
    }

    /**
     * @param string                  $etag
     * @param \DateTimeImmutable|null $date
     * @param File                    $file
     * @param string                  $contentDisposition
     * @param int                     $width
     * @param int                     $height
     * @return StreamedResponse
     */
    private function createPreviewPdfResponse(
        string $etag,
        ?\DateTimeImmutable $date,
        File $file,
        string $contentDisposition,
        int $width,
        int $height
    ): StreamedResponse {
        $response = $this->prepareStreamedResponse($etag, $date, $file, $contentDisposition, 'image/png', 'png');
        return $response->setCallback(
            $this->createResponseCallback(
                $file,
                $width,
                $height,
                function (\Imagick $im): \Imagick {
                    $im->setIteratorIndex(0);
                    $im->setBackgroundColor('white');
                    $im->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                    $im->setResolution(150.0, 150.0);
                    $im->setFormat('png');
                    return $im;
                }
            )
        );
    }
}
