<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use App\Application\File\Command\RemoveFile;
use App\Domain\Model\File\FileRepository;
use App\Infrastructure\Controller\PagingRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FileController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/files")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class FileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request        $request
     * @param FileRepository $fileRepository
     * @return Response
     */
    public function list(Request $request, FileRepository $fileRepository): Response
    {
        $activeFilters     = [];
        $addFilterIfActive = function (string $filter) use ($request, &$activeFilters) {
            $value = $request->query->get($filter);
            if ($value) {
                $activeFilters[$filter] = [
                    'value'     => $value,
                    'removeUrl' => $this->generateUrl(
                        'app_admin_file_list',
                        array_merge(
                            $request->query->all(),
                            [
                                'page'  => null,
                                $filter => null,
                            ]
                        )
                    ),
                ];
            }
            return $value;
        };
        $currentFilters    = [
            'type'   => $addFilterIfActive('type'),
            'origin' => $addFilterIfActive('origin'),
        ];

        $paging = PagingRequest::create($request);

        return $this->render(
            'admin/file/list.html.twig',
            [
                'activeFilters'  => $activeFilters,
                'currentFilters' => $currentFilters,
                'files'          => $fileRepository->findPaged(
                    $currentFilters['type'],
                    $currentFilters['origin'],
                    $paging->getPage(),
                    $paging->getLimit()
                ),
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

        $remove = RemoveFile::remove($file);
        $commandBus->dispatch($remove);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
