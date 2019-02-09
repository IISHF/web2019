<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 10:12
 */

namespace App\Controller\Event;

use App\Application\Event\Game\Command\CreateGame;
use App\Application\Event\Game\Command\DeleteGame;
use App\Application\Event\Game\Command\UpdateGame;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\Game\Game;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Game\Form\CreateGameType;
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
 * @package App\Controller\Event
 *
 * @Route(
 *      "/events/{event}/games",
 *      requirements={"event": "%routing.uuid%"}
 * )
 * @ParamConverter(
 *      name="event",
 *      class="App\Domain\Model\Event\Event",
 *      converter="app.event"
 * )
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
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/game/create.html.twig',
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
            'event/game/detail.html.twig',
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
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/game/update.html.twig',
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
            'app_event_event_detail',
            [
                'season' => $event->getSeason(),
                'event'  => $event->getSlug(),
            ]
        );
    }
}
