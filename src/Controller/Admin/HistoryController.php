<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use App\Application\History\Command\CreateHallOfFameEntry;
use App\Application\History\Command\DeleteHallOfFameEntry;
use App\Application\History\Command\UpdateHallOfFameEntry;
use App\Domain\Model\History\HallOfFameEntry;
use App\Domain\Model\History\HallOfFameRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\History\Form\CreateHallOfFameEntryType;
use App\Infrastructure\History\Form\UpdateHallOfFameEntryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HistoryController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/history")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class HistoryController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param HallOfFameRepository $hallOfFameRepository
     * @return Response
     */
    public function list(HallOfFameRepository $hallOfFameRepository): Response
    {
        return $this->render(
            'admin/history/list.html.twig',
            [
                'entries' => $hallOfFameRepository->findAll(),
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
        $create = CreateHallOfFameEntry::create();
        $form   = $this->createForm(CreateHallOfFameEntryType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new hallf of fame entry has been created.');

            return $this->redirectToRoute('app_admin_history_list');
        }

        return $this->render(
            'admin/history/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{entry}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"entry": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="entry",
     *      class="App\Domain\Model\History\HallOfFameEntry",
     *      converter="app.hall_of_fame_entry"
     * )
     * @Security("is_granted('HALL_OF_FAME_ENTRY_EDIT', entry)")
     *
     * @param Request             $request
     * @param HallOfFameEntry     $entry
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, HallOfFameEntry $entry, MessageBusInterface $commandBus): Response
    {
        $update = UpdateHallOfFameEntry::update($entry);
        $form   = $this->createForm(UpdateHallOfFameEntryType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The hall of fame entry has been updated.');

            return $this->redirectToRoute('app_admin_history_list');
        }

        return $this->render(
            'admin/history/update.html.twig',
            [
                'entry' => $entry,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{entry}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"entry": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="entry",
     *      class="App\Domain\Model\History\HallOfFameEntry",
     *      converter="app.hall_of_fame_entry"
     * )
     * @Security("is_granted('HALL_OF_FAME_ENTRY_DELETE', entry)")
     *
     * @param Request             $request
     * @param HallOfFameEntry     $entry
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, HallOfFameEntry $entry, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteHallOfFameEntry::delete($entry);

        $this->handleCsrfCommand($delete, 'HALL_OF_FAME_ENTRY_DELETE_' . $entry->getId(), $request, $commandBus);

        $this->addFlash('success', 'The hall of fame entry has been deleted.');

        return $this->redirectToRoute('app_admin_history_list');
    }
}
