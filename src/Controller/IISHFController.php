<?php

namespace App\Controller;

use App\Domain\Model\Committee\CommitteeRepository;
use App\Domain\Model\Staff\StaffMemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IISHFController
 *
 * @package App\Controller
 *
 * @Route("/iishf")
 */
class IISHFController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param StaffMemberRepository $memberRepository
     * @param CommitteeRepository   $committeeRepository
     * @return Response
     */
    public function index(StaffMemberRepository $memberRepository, CommitteeRepository $committeeRepository): Response
    {
        return $this->render(
            'iishf/index.html.twig',
            [
                'staff'      => $memberRepository->findAll(),
                'committees' => $committeeRepository->findAll(),
            ]
        );
    }
}
