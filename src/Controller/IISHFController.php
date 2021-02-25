<?php

namespace App\Controller;

use App\Domain\Model\Committee\CommitteeMember;
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
    use RedirectToFileController;

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
                'presidium'  => $memberRepository->findPresidium(),
                'officers'   => $memberRepository->findOfficers(),
                'committees' => $committeeRepository->findAllSorted(),
            ]
        );
    }

    /**
     * @Route(
     *     "/staff/{member}/image",
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
        return $this->redirectToFile($member->getImage());
    }

    /**
     * @Route(
     *     "/committees/{member}/image",
     *     methods={"GET"},
     *     requirements={"member": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Committee\CommitteeMember",
     *      converter="app.committee_member"
     * )
     *
     * @param CommitteeMember $member
     * @return Response
     */
    public function committeeMemberImage(CommitteeMember $member): Response
    {
        return $this->redirectToFile($member->getImage());
    }
}
