<?php

namespace App\Controller;

use App\Domain\Model\Committee\CommitteeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommitteesController
 *
 * @package App\Controller
 *
 * @Route("/iishf/committees")
 */
class CommitteesController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param CommitteeRepository $committeeRepository
     * @return Response
     */
    public function index(CommitteeRepository $committeeRepository): Response
    {
        return $this->render(
            'committees/index.html.twig',
            [
                'committees' => $committeeRepository->findAll(),
            ]
        );
    }
}
