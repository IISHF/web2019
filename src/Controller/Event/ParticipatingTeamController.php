<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:48
 */

namespace App\Controller\Event;

use App\Application\Event\Command\AddParticipatingTeam;
use App\Application\Event\Command\RemoveParticipatingTeam;
use App\Application\Event\Command\UpdateParticipatingTeam;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\ParticipatingTeamRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Form\AddParticipatingTeamType;
use App\Infrastructure\Event\Form\UpdateParticipatingTeamType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ParticipatingTeamController
 *
 * @package App\Controller\Event
 *
 * @Route(
 *      "/events/{event}/teams",
 *      requirements={"event": "%routing.uuid%"}
 * )
 * @ParamConverter(
 *      name="event",
 *      class="App\Domain\Model\Event\Event",
 *      converter="app.event"
 * )
 */
class ParticipatingTeamController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("/add", methods={"GET", "POST"})
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function add(Request $request, Event $event, MessageBusInterface $commandBus): Response
    {
        $add  = AddParticipatingTeam::add($event->getId());
        $form = $this->createForm(AddParticipatingTeamType::class, $add);

        if ($this->handleForm($add, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new participating team has been added.');

            return $this->redirectToRoute(
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/team/add.html.twig',
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{teamId}",
     *      methods={"GET"},
     *      requirements={"teamId": "%routing.uuid%"}
     * )
     *
     * @param Event                       $event
     * @param string                      $teamId
     * @param ParticipatingTeamRepository $teamRepository
     * @return Response
     */
    public function detail(Event $event, string $teamId, ParticipatingTeamRepository $teamRepository): Response
    {
        $team = $teamRepository->findById($teamId);
        if (!$team) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'event/team/detail.html.twig',
            [
                'event' => $event,
                'team'  => $team,
            ]
        );
    }

    /**
     * @Route(
     *     "/{teamId}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"teamId": "%routing.uuid%"}
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request                     $request
     * @param Event                       $event
     * @param string                      $teamId
     * @param ParticipatingTeamRepository $teamRepository
     * @param MessageBusInterface         $commandBus
     * @return Response
     */
    public function update(
        Request $request,
        Event $event,
        string $teamId,
        ParticipatingTeamRepository $teamRepository,
        MessageBusInterface $commandBus
    ): Response {
        $team = $teamRepository->findById($teamId);
        if (!$team) {
            throw $this->createNotFoundException();
        }

        $update = UpdateParticipatingTeam::update($team);
        $form   = $this->createForm(UpdateParticipatingTeamType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The participating team has been updated.');

            return $this->redirectToRoute(
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/team/update.html.twig',
            [
                'event' => $event,
                'team'  => $team,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{teamId}/remove",
     *     methods={"POST", "DELETE"},
     *     requirements={"teamId": "%routing.uuid%"}
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request                     $request
     * @param Event                       $event
     * @param string                      $teamId
     * @param ParticipatingTeamRepository $teamRepository
     * @param MessageBusInterface         $commandBus
     * @return Response
     */
    public function remove(
        Request $request,
        Event $event,
        string $teamId,
        ParticipatingTeamRepository $teamRepository,
        MessageBusInterface $commandBus
    ): Response {
        $team = $teamRepository->findById($teamId);
        if (!$team) {
            throw $this->createNotFoundException();
        }

        $withdraw = RemoveParticipatingTeam::remove($team);

        $this->handleCsrfCommand($withdraw, 'event_team_remove_' . $team->getId(), $request, $commandBus);

        $this->addFlash('success', 'The participating team has been removed.');

        return $this->redirectToRoute(
            'app_event_event_detail',
            [
                'season' => $event->getSeason(),
                'event'  => $event->getSlug(),
            ]
        );
    }
}
