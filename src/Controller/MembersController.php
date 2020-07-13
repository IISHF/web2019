<?php

namespace App\Controller;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembersController
 *
 * @package App\Controller
 *
 * @Route("/members")
 */
class MembersController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param NationalGoverningBodyRepository $ngbRepository
     * @return Response
     */
    public function index(NationalGoverningBodyRepository $ngbRepository): Response
    {
        return $this->render(
            'members/index.twig',
            [
                'ngbs' => $ngbRepository->findAll(),
            ]
        );
    }
}
