<?php

namespace App\Controller;

use App\Domain\Model\Committee\CommitteeRepository;
use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @Route(
     *     "/{member}/image",
     *     methods={"GET"},
     *     requirements={"member": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Staff\StaffMember",
     *      converter="app.staff_member"
     * )
     *
     * @param StaffMember $member
     * @return Response
     */
    public function staffMemberImage(StaffMember $member): Response
    {
        $image = $member->getImage();
        if (!$image) {
            throw $this->createNotFoundException();
        }
        return $this->redirectToRoute(
            'app_file_download',
            [
                'name' => $image->getName(),
            ],
            Response::HTTP_SEE_OTHER
        );
    }
}
