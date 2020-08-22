<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller;

use App\Domain\Model\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trait RedirectToFileController
 *
 * @package App\Controller
 *
 * @method NotFoundHttpException createNotFoundException()
 * @method RedirectResponse redirectToRoute(string $route, array $parameters, int $status)
 */
trait RedirectToFileController
{
    /**
     * @param File|null $file
     * @return Response
     */
    private function redirectToFile(?File $file): Response
    {
        if (!$file) {
            throw $this->createNotFoundException();
        }
        return $this->redirectToRoute(
            'app_file_download',
            [
                'name' => $file->getName(),
            ],
            Response::HTTP_SEE_OTHER
        );
    }
}
