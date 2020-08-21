<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller;

use App\Application\File\ImageResizer;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 *
 * @package App\Controller\Admin
 *
 * @Route("/files")
 */
class FileController extends AbstractController
{
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
     * @param ImageResizer   $imageResizer
     * @return Response
     */
    public function download(
        string $name,
        Request $request,
        FileRepository $fileRepository,
        ImageResizer $imageResizer
    ): Response {
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
        if ($width > 0 || $height > 0) {
            $etag = sha1($etag . '/w=' . $width . '/h=' . $height);
            if ($file->isImage()) {
                $resizeImage = true;
            } elseif ($file->isPdf()) {
                $pdfPreview = true;
            }
        } elseif ($request->query->has('preview') && $file->isPdf()) {
            $pdfPreview = true;
        }

        $response = (new Response())
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
            $response = $this->createStreamedResponse(
                static function () use ($file, $width, $height, $imageResizer) {
                    echo $imageResizer->resizeImage($file, $width, $height);
                },
                $file,
                $contentDisposition
            );
        } elseif ($pdfPreview) {
            $response = $this->createStreamedResponse(
                static function () use ($file, $width, $height, $imageResizer) {
                    echo $imageResizer->resizePdf($file, $width, $height);
                },
                $file,
                $contentDisposition,
                'image/png',
                'png'
            );
        } else {
            $response = (new BinaryFileResponse($file->writeTo(null)))
                ->setContentDisposition(
                    $contentDisposition,
                    $file->getClientName(),
                    $file->getSafeClientName()
                )
                ->deleteFileAfterSend();
        }

        return $response->setEtag($etag)
                        ->setLastModified($date)
                        ->setPublic();
    }

    /**
     * @param callable    $callback
     * @param File        $file
     * @param string      $contentDisposition
     * @param string|null $mimeType
     * @param string|null $addExtension
     * @return StreamedResponse
     */
    private function createStreamedResponse(
        callable $callback,
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

        $response = new StreamedResponse($callback);
        $headers = $response->headers;
        $headers->set('Content-Type', $mimeType ?? $file->getMimeType());
        $headers->set(
            'Content-Disposition',
            $headers->makeDisposition($contentDisposition, $filename, $safeFilename)
        );
        return $response;
    }
}
