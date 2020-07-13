<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin\Event;

use App\Application\Event\Command\DeleteEvent;
use App\Application\Event\Command\EuropeanChampionship\CreateEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateAnnouncedEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateSanctionedEuropeanChampionship;
use App\Application\Event\Command\EuropeanCup\CreateEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateAnnouncedEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateSanctionedEuropeanCup;
use App\Application\Event\Command\Tournament\CreateTournament;
use App\Application\Event\Command\Tournament\UpdateSanctionedTournament;
use App\Application\Event\Command\Tournament\UpdateTournament;
use App\Application\Event\Command\Workflow\EventWorkflowCommand;
use App\Domain\Model\Event\Application\TitleEventApplicationRepository;
use App\Domain\Model\Event\EuropeanChampionship;
use App\Domain\Model\Event\EuropeanCup;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\Game\GameRepository;
use App\Domain\Model\Event\Team\ParticipatingTeamRepository;
use App\Domain\Model\Event\TitleEvent;
use App\Domain\Model\Event\Tournament;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Form\EuropeanChampionship\CreateEuropeanChampionshipType;
use App\Infrastructure\Event\Form\EuropeanChampionship\UpdateAnnouncedEuropeanChampionshipType;
use App\Infrastructure\Event\Form\EuropeanChampionship\UpdateEuropeanChampionshipType;
use App\Infrastructure\Event\Form\EuropeanChampionship\UpdateSanctionedEuropeanChampionshipType;
use App\Infrastructure\Event\Form\EuropeanCup\CreateEuropeanCupType;
use App\Infrastructure\Event\Form\EuropeanCup\UpdateAnnouncedEuropeanCupType;
use App\Infrastructure\Event\Form\EuropeanCup\UpdateEuropeanCupType;
use App\Infrastructure\Event\Form\EuropeanCup\UpdateSanctionedEuropeanCupType;
use App\Infrastructure\Event\Form\Tournament\CreateTournamentType;
use App\Infrastructure\Event\Form\Tournament\UpdateSanctionedTournamentType;
use App\Infrastructure\Event\Form\Tournament\UpdateTournamentType;
use App\Infrastructure\Workflow\WorkflowMetadata;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry as WorkflowRegistry;

/**
 * Class EventController
 *
 * @package App\Controller\Admin\Event
 *
 * @Route("/admin/events")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class EventController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     * @Route("/current", methods={"GET"})
     *
     * @return Response
     */
    public function current(): Response
    {
        return $this->redirectToRoute('app_admin_event_event_season', ['season' => idate('Y')]);
    }

    /**
     * @Route("/{season<\d{4}>}", methods={"GET"})
     *
     * @param int             $season
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function season(int $season, EventRepository $eventRepository): Response
    {
        $championships = $eventRepository->findEuropeanChampionShipsForSeason($season);
        $cups          = $eventRepository->findEuropeanCupsForSeason($season);
        $tournaments   = $eventRepository->findTournamentsForSeason($season);

        return $this->render(
            'admin/event/season.html.twig',
            [
                'season'        => $season,
                'championships' => $championships,
                'cups'          => $cups,
                'tournaments'   => $tournaments,
            ]
        );
    }

    /**
     * @Route("/create/championship", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createChampionship(Request $request, MessageBusInterface $commandBus): Response
    {
        $create = CreateEuropeanChampionship::create();
        $form   = $this->createForm(CreateEuropeanChampionshipType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new European Championship has been created.');

            return $this->redirectToRoute('app_admin_event_event_season', ['season' => $create->getSeason()]);
        }

        return $this->render(
            'admin/event/create_championship.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/create/cup", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createCup(Request $request, MessageBusInterface $commandBus): Response
    {
        $create = CreateEuropeanCup::create();
        $form   = $this->createForm(CreateEuropeanCupType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new European Cup has been created.');

            return $this->redirectToRoute('app_admin_event_event_season', ['season' => $create->getSeason()]);
        }

        return $this->render(
            'admin/event/create_cup.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/create/tournament", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function createTournament(Request $request, MessageBusInterface $commandBus): Response
    {
        $create = CreateTournament::create();
        $form   = $this->createForm(CreateTournamentType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new tournament has been created.');

            return $this->redirectToRoute('app_admin_event_event_season', ['season' => $create->getSeason()]);
        }

        return $this->render(
            'admin/event/create_tournament.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{season<\d{4}>}/{event}",
     *      methods={"GET"},
     *     requirements={"event": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="event",
     *      class="App\Domain\Model\Event\Event",
     *      converter="app.event"
     * )
     *
     * @param Event                           $event
     * @param TitleEventApplicationRepository $applicationRepository
     * @param ParticipatingTeamRepository     $teamRepository
     * @param GameRepository                  $gameRepository
     * @return Response
     */
    public function detail(
        Event $event,
        TitleEventApplicationRepository $applicationRepository,
        ParticipatingTeamRepository $teamRepository,
        GameRepository $gameRepository
    ): Response {
        if ($event instanceof EuropeanChampionship) {
            $template = 'admin/event/detail_championship.html.twig';
        } elseif ($event instanceof EuropeanCup) {
            $template = 'admin/event/detail_cup.html.twig';
        } elseif ($event instanceof Tournament) {
            $template = 'admin/event/detail_tournament.html.twig';
        } else {
            throw $this->createNotFoundException();
        }

        if ($event instanceof TitleEvent) {
            $applications = $applicationRepository->findForEvent($event);
        } else {
            $applications = [];
        }

        $teams = $teamRepository->findForEvent($event);
        $games = $gameRepository->findForEvent($event);

        return $this->render(
            $template,
            [
                'event'        => $event,
                'applications' => $applications,
                'teams'        => $teams,
                'games'        => $games,
            ]
        );
    }

    /**
     * @Route(
     *     "/{event}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"event": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="event",
     *      class="App\Domain\Model\Event\Event",
     *      converter="app.event"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Event $event, MessageBusInterface $commandBus): Response
    {
        if ($event instanceof EuropeanChampionship) {
            if ($event->isSanctioned()) {
                $update   = UpdateSanctionedEuropeanChampionship::update($event);
                $formType = UpdateSanctionedEuropeanChampionshipType::class;
            } elseif ($event->isAnnounced()) {
                $update   = UpdateAnnouncedEuropeanChampionship::update($event);
                $formType = UpdateAnnouncedEuropeanChampionshipType::class;
            } else {
                $update   = UpdateEuropeanChampionship::update($event);
                $formType = UpdateEuropeanChampionshipType::class;
            }
            $template       = 'admin/event/update_championship.html.twig';
            $successMessage = 'The European Championship has been updated.';
        } elseif ($event instanceof EuropeanCup) {
            if ($event->isSanctioned()) {
                $update   = UpdateSanctionedEuropeanCup::update($event);
                $formType = UpdateSanctionedEuropeanCupType::class;
            } elseif ($event->isAnnounced()) {
                $update   = UpdateAnnouncedEuropeanCup::update($event);
                $formType = UpdateAnnouncedEuropeanCupType::class;
            } else {
                $update   = UpdateEuropeanCup::update($event);
                $formType = UpdateEuropeanCupType::class;
            }
            $template       = 'admin/event/update_cup.html.twig';
            $successMessage = 'The European Cup has been updated.';
        } elseif ($event instanceof Tournament) {
            if ($event->isSanctioned()) {
                $update   = UpdateSanctionedTournament::update($event);
                $formType = UpdateSanctionedTournamentType::class;
            } else {
                $update   = UpdateTournament::update($event);
                $formType = UpdateTournamentType::class;
            }
            $template       = 'admin/event/update_tournament.html.twig';
            $successMessage = 'The tournament has been updated.';
        } else {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($formType, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', $successMessage);

            return $this->redirectToRoute('app_admin_event_event_season', ['season' => $event->getSeason()]);
        }

        return $this->render(
            $template,
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{event}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"event": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="event",
     *      class="App\Domain\Model\Event\Event",
     *      converter="app.event"
     * )
     * @Security("is_granted('EVENT_DELETE', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, Event $event, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteEvent::delete($event);

        $this->handleCsrfCommand($delete, 'event_delete_' . $event->getId(), $request, $commandBus);

        $this->addFlash('success', 'The event has been deleted.');

        return $this->redirectToRoute('app_admin_event_event_season', ['season' => $event->getSeason()]);
    }

    /**
     * @Route(
     *     "/{event}/workflow/{transition<\w+>}",
     *     methods={"GET", "POST"},
     *     requirements={"event": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="event",
     *      class="App\Domain\Model\Event\Event",
     *      converter="app.event"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param string              $transition
     * @param MessageBusInterface $commandBus
     * @param WorkflowRegistry    $workflowRegistry
     * @return Response
     */
    public function workflow(
        Request $request,
        Event $event,
        string $transition,
        MessageBusInterface $commandBus,
        WorkflowRegistry $workflowRegistry
    ): Response {
        if ($event instanceof EuropeanChampionship) {
            $workflow = 'title_event_sanctioning';
            $template = 'admin/event/workflow_championship.html.twig';
        } elseif ($event instanceof EuropeanCup) {
            $workflow = 'title_event_sanctioning';
            $template = 'admin/event/workflow_cup.html.twig';
        } elseif ($event instanceof Tournament) {
            $workflow = 'tournament_sanctioning';
            $template = 'admin/event/workflow_tournament.html.twig';
        } else {
            throw $this->createNotFoundException();
        }

        $transitionMeta = WorkflowMetadata::find($workflowRegistry, $event, $workflow)
                                          ->findEnabledTransition($transition);
        if (!$transitionMeta) {
            throw new BadRequestHttpException();
        }

        $successMessage = $transitionMeta->getMetadata('success_message', 'The article has been updated.');

        $command = EventWorkflowCommand::create($event, $transition);
        if (($formType = $transitionMeta->getFormType()) !== null) {
            $form = $this->createForm($formType, $command);
            if ($this->handleForm($command, $form, $request, $commandBus)) {
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('app_admin_event_event_season', ['season' => $event->getSeason()]);
            }

            return $this->render(
                $template,
                [
                    'event'      => $event,
                    'transition' => $transitionMeta->getTransition(),
                    'form'       => $form->createView(),
                ]
            );
        }

        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        $this->handleCsrfCommand(
            $command,
            'event_' . $event->getId() . '_' . $transitionMeta->getName(),
            $request,
            $commandBus
        );

        $this->addFlash('success', $successMessage);

        return $this->redirectToRoute('app_admin_event_event_season', ['season' => $event->getSeason()]);
    }
}
