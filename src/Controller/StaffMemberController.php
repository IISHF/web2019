<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:02
 */

namespace App\Controller;

use App\Application\Staff\Command\CreateStaffMember;
use App\Application\Staff\Command\DeleteStaffMember;
use App\Application\Staff\Command\UpdateStaffMember;
use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Staff\Form\CreateStaffMemberType;
use App\Infrastructure\Staff\Form\UpdateStaffMemberType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StaffMemberController
 *
 * @package App\Controller
 *
 * @Route("/iishf/staff")
 */
class StaffMemberController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param StaffMemberRepository $memberRepository
     * @return Response
     */
    public function list(StaffMemberRepository $memberRepository): Response
    {
        return $this->render(
            'staff/list.html.twig',
            [
                'members' => $memberRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/create", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        $create = CreateStaffMember::create();
        $form   = $this->createForm(CreateStaffMemberType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new staff member has been created.');

            return $this->redirectToRoute('app_staffmember_list');
        }

        return $this->render(
            'staff/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{member}",
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
    public function detail(StaffMember $member): Response
    {
        return $this->render(
            'staff/detail.html.twig',
            [
                'member' => $member,
            ]
        );
    }

    /**
     * @Route(
     *     "/{member}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"member": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Staff\StaffMember",
     *      converter="app.staff_member"
     * )
     * @Security("is_granted('STAFF_MEMBER_EDIT', member)")
     *
     * @param Request             $request
     * @param StaffMember         $member
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, StaffMember $member, MessageBusInterface $commandBus): Response
    {
        $update = UpdateStaffMember::update($member);
        $form   = $this->createForm(UpdateStaffMemberType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The staff member has been updated.');

            return $this->redirectToRoute('app_staffmember_list');
        }

        return $this->render(
            'staff/update.html.twig',
            [
                'member' => $member,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{member}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"member": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Staff\StaffMember",
     *      converter="app.staff_member"
     * )
     * @Security("is_granted('STAFF_MEMBER_DELETE', member)")
     *
     * @param Request             $request
     * @param StaffMember         $member
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, StaffMember $member, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteStaffMember::delete($member);

        $this->handleCsrfCommand($delete, 'staff_member_delete_' . $member->getId(), $request, $commandBus);

        $this->addFlash('success', 'The staff member has been deleted.');

        return $this->redirectToRoute('app_staffmember_list');
    }
}
