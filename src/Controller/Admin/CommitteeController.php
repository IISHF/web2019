<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use App\Application\Committee\Command\CreateCommittee;
use App\Application\Committee\Command\CreateCommitteeMember;
use App\Application\Committee\Command\DeleteCommittee;
use App\Application\Committee\Command\DeleteCommitteeMember;
use App\Application\Committee\Command\UpdateCommittee;
use App\Application\Committee\Command\UpdateCommitteeMember;
use App\Domain\Model\Committee\Committee;
use App\Domain\Model\Committee\CommitteeMember;
use App\Domain\Model\Committee\CommitteeRepository;
use App\Infrastructure\Committee\Form\CreateCommitteeMemberType;
use App\Infrastructure\Committee\Form\CreateCommitteeType;
use App\Infrastructure\Committee\Form\UpdateCommitteeMemberType;
use App\Infrastructure\Committee\Form\UpdateCommitteeType;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommitteeController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/committees")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class CommitteeController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param CommitteeRepository $committeeRepository
     * @return Response
     */
    public function list(CommitteeRepository $committeeRepository): Response
    {
        return $this->render(
            'admin/committee/list.html.twig',
            [
                'committees' => $committeeRepository->findAll(),
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
        $create = CreateCommittee::create();
        $form   = $this->createForm(CreateCommitteeType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new committee has been created.');

            return $this->redirectToRoute('app_admin_committee_list');
        }

        return $this->render(
            'admin/committee/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{committee}",
     *     methods={"GET"},
     *     requirements={"committee": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="committee",
     *      class="App\Domain\Model\Committee\Committee",
     *      converter="app.committee",
     *      options={"with_members": true}
     * )
     *
     * @param Committee $committee
     * @return Response
     */
    public function detail(Committee $committee): Response
    {
        return $this->render(
            'admin/committee/detail.html.twig',
            [
                'committee' => $committee,
            ]
        );
    }

    /**
     * @Route(
     *     "/{committee}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"committee": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="committee",
     *      class="App\Domain\Model\Committee\Committee",
     *      converter="app.committee"
     * )
     * @Security("is_granted('COMMITTEE_EDIT', committee)")
     *
     * @param Request             $request
     * @param Committee           $committee
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Committee $committee, MessageBusInterface $commandBus): Response
    {
        $update = UpdateCommittee::update($committee);
        $form   = $this->createForm(UpdateCommitteeType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The committee has been updated.');

            return $this->redirectToRoute('app_admin_committee_list');
        }

        return $this->render(
            'admin/committee/update.html.twig',
            [
                'committee' => $committee,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{committee}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"committee": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="committee",
     *      class="App\Domain\Model\Committee\Committee",
     *      converter="app.committee"
     * )
     * @Security("is_granted('COMMITTEE_DELETE', committee)")
     *
     * @param Request             $request
     * @param Committee           $committee
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, Committee $committee, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteCommittee::delete($committee);

        $this->handleCsrfCommand($delete, 'committee_delete_' . $committee->getId(), $request, $commandBus);

        $this->addFlash('success', 'The committee has been deleted.');

        return $this->redirectToRoute('app_admin_committee_list');

    }

    /**
     * @Route(
     *     "/{committee}/create",
     *     methods={"GET", "POST"},
     *     requirements={"committee": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="committee",
     *      class="App\Domain\Model\Committee\Committee",
     *      converter="app.committee"
     * )
     * @Security("is_granted('COMMITTEE_EDIT', committee)")
     *
     * @param Request             $request
     * @param Committee           $committee
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createMember(Request $request, Committee $committee, MessageBusInterface $commandBus): Response
    {
        $create = CreateCommitteeMember::create($committee->getId());
        $form   = $this->createForm(CreateCommitteeMemberType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new committee member has been created.');

            return $this->redirectToRoute('app_admin_committee_detail', ['committee' => $committee->getSlug()]);
        }

        return $this->render(
            'admin/committee/member_create.html.twig',
            [
                'committee' => $committee,
                'form'      => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{committee}/{member}/edit",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "committee": "%routing.uuid%",
     *          "member": "%routing.uuid%"
     *      }
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Committee\CommitteeMember",
     *      converter="app.committee_member"
     * )
     * @Security("is_granted('COMMITTEE_MEMBER_EDIT', member)")
     *
     * @param Request             $request
     * @param CommitteeMember     $member
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function updateMember(Request $request, CommitteeMember $member, MessageBusInterface $commandBus): Response
    {
        $update = UpdateCommitteeMember::update($member);
        $form   = $this->createForm(UpdateCommitteeMemberType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The committee member has been updated.');

            return $this->redirectToRoute(
                'app_admin_committee_detail',
                ['committee' => $member->getCommittee()->getSlug()]
            );
        }

        return $this->render(
            'admin/committee/member_update.html.twig',
            [
                'member' => $member,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{committee}/{member}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={
     *          "committee": "%routing.uuid%",
     *          "member": "%routing.uuid%"
     *      }
     * )
     * @ParamConverter(
     *      name="member",
     *      class="App\Domain\Model\Committee\CommitteeMember",
     *      converter="app.committee_member"
     * )
     * @Security("is_granted('COMMITTEE_MEMBER_DELETE', member)")
     *
     * @param Request             $request
     * @param CommitteeMember     $member
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function deleteMember(Request $request, CommitteeMember $member, MessageBusInterface $commandBus): Response
    {
        $committee = $member->getCommittee();
        $delete    = DeleteCommitteeMember::delete($member);

        $this->handleCsrfCommand($delete, 'committee_member_delete_' . $member->getId(), $request, $commandBus);

        $this->addFlash('success', 'The committee member has been deleted.');

        return $this->redirectToRoute('app_admin_committee_detail', ['committee' => $committee->getSlug()]);
    }
}
