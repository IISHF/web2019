<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin\Event;

use App\Application\Event\Game\Command\ChangeFixture;
use App\Application\Event\Game\Command\CreateGame;
use App\Application\Event\Game\Command\DeleteGame;
use App\Application\Event\Game\Command\RecordResult;
use App\Application\Event\Game\Command\RescheduleGame;
use App\Application\Event\Game\Command\UpdateGame;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\Game\Game;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Game\Form\ChangeFixtureType;
use App\Infrastructure\Event\Game\Form\CreateGameType;
use App\Infrastructure\Event\Game\Form\RecordResultType;
use App\Infrastructure\Event\Game\Form\RescheduleGameType;
use App\Infrastructure\Event\Game\Form\UpdateGameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GameController
 *
 * @package App\Controller\Admin\Event
 *
 * @Route(
 *      "/admin/events/{event}/games",
 *      requirements={"event": "%routing.uuid%"}
 * )
 * @ParamConverter(
 *      name="event",
 *      class="App\Domain\Model\Event\Event",
 *      converter="app.event"
 * )
 * @Security("is_granted('ROLE_ADMIN')")
 */
class GameController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("/create", methods={"GET", "POST"})
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, Event $event, MessageBusInterface $commandBus): Response
    {
        $create = CreateGame::create($event->getId());
        $form   = $this->createForm(CreateGameType::class, $create, ['event' => $event]);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new game has been created.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/game/create.html.twig',
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}",
     *      methods={"GET"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     *
     * @param Event $event
     * @param Game  $game
     * @return Response
     */
    public function detail(Event $event, Game $game): Response
    {
        return $this->render(
            'admin/event/game/detail.html.twig',
            [
                'event' => $event,
                'game'  => $game,
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}/edit",
     *      methods={"GET", "POST"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     * @Security("is_granted('EVENT_GAME_EDIT', game)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param Game                $game
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, Event $event, Game $game, MessageBusInterface $commandBus): Response
    {
        $update = UpdateGame::update($game);
        $form   = $this->createForm(UpdateGameType::class, $update, ['event' => $event]);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The game has been updated.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/game/update.html.twig',
            [
                'event' => $event,
                'game'  => $game,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}/change-fixture",
     *      methods={"GET", "POST"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     * @Security("is_granted('EVENT_GAME_EDIT', game)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param Game                $game
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function changeFixture(Request $request, Event $event, Game $game, MessageBusInterface $commandBus): Response
    {
        $update = ChangeFixture::change($game);
        $form   = $this->createForm(ChangeFixtureType::class, $update, ['event' => $event]);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The game fixture has been updated.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/game/change_fixture.html.twig',
            [
                'event' => $event,
                'game'  => $game,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}/record-result",
     *      methods={"GET", "POST"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     * @Security("is_granted('EVENT_GAME_EDIT', game)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param Game                $game
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function recordResult(Request $request, Event $event, Game $game, MessageBusInterface $commandBus): Response
    {
        $update = RecordResult::record($game);
        $form   = $this->createForm(RecordResultType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The game result has been recorded.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/game/record_result.html.twig',
            [
                'event' => $event,
                'game'  => $game,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}/reschedule",
     *      methods={"GET", "POST"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     * @Security("is_granted('EVENT_GAME_EDIT', game)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param Game                $game
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function reschedule(Request $request, Event $event, Game $game, MessageBusInterface $commandBus): Response
    {
        $update = RescheduleGame::reschedule($game);
        $form   = $this->createForm(RescheduleGameType::class, $update, ['event' => $event]);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The game has been rescheduled.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/game/reschedule_game.html.twig',
            [
                'event' => $event,
                'game'  => $game,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{game}/delete",
     *      methods={"POST", "DELETE"},
     *      requirements={"game": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="game",
     *      class="App\Domain\Model\Event\Game\Game",
     *      converter="app.event_game"
     * )
     * @Security("is_granted('EVENT_GAME_DELETE', game)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param Game                $game
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, Event $event, Game $game, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteGame::delete($game);

        $this->handleCsrfCommand($delete, 'event_game_delete_' . $game->getId(), $request, $commandBus);

        $this->addFlash('success', 'The game has been deleted.');

        return $this->redirectToRoute(
            'app_admin_event_event_detail',
            [
                'season' => $event->getSeason(),
                'event'  => $event->getSlug(),
            ]
        );
    }
}
