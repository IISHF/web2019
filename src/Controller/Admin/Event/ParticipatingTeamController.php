<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin\Event;

use App\Application\Event\Team\Command\AddParticipatingTeam;
use App\Application\Event\Team\Command\RemoveParticipatingTeam;
use App\Application\Event\Team\Command\UpdateParticipatingTeam;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\Team\ParticipatingTeam;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Team\Form\AddParticipatingTeamType;
use App\Infrastructure\Event\Team\Form\UpdateParticipatingTeamType;
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
 * @package App\Controller\Admin\Event
 *
 * @Route(
 *      "/admin/events/{event}/teams",
 *      requirements={"event": "%routing.uuid%"}
 * )
 * @ParamConverter(
 *      name="event",
 *      class="App\Domain\Model\Event\Event",
 *      converter="app.event"
 * )
 * @Security("is_granted('ROLE_ADMIN')")
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
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/team/add.html.twig',
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{team}",
     *      methods={"GET"},
     *      requirements={"team": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="team",
     *      class="App\Domain\Model\Event\Team\ParticipatingTeam",
     *      converter="app.event_team"
     * )
     *
     * @param Event             $event
     * @param ParticipatingTeam $team
     * @return Response
     */
    public function detail(Event $event, ParticipatingTeam $team): Response
    {
        return $this->render(
            'admin/event/team/detail.html.twig',
            [
                'event' => $event,
                'team'  => $team,
            ]
        );
    }

    /**
     * @Route(
     *     "/{team}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"team": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="team",
     *      class="App\Domain\Model\Event\Team\ParticipatingTeam",
     *      converter="app.event_team"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param ParticipatingTeam   $team
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(
        Request $request,
        Event $event,
        ParticipatingTeam $team,
        MessageBusInterface $commandBus
    ): Response {
        $update = UpdateParticipatingTeam::update($team);
        $form   = $this->createForm(UpdateParticipatingTeamType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The participating team has been updated.');

            return $this->redirectToRoute(
                'app_admin_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'admin/event/team/update.html.twig',
            [
                'event' => $event,
                'team'  => $team,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{team}/remove",
     *     methods={"POST", "DELETE"},
     *     requirements={"team": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="team",
     *      class="App\Domain\Model\Event\Team\ParticipatingTeam",
     *      converter="app.event_team"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param ParticipatingTeam   $team
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function remove(
        Request $request,
        Event $event,
        ParticipatingTeam $team,
        MessageBusInterface $commandBus
    ): Response {
        $withdraw = RemoveParticipatingTeam::remove($team);

        $this->handleCsrfCommand($withdraw, 'event_team_remove_' . $team->getId(), $request, $commandBus);

        $this->addFlash('success', 'The participating team has been removed.');

        return $this->redirectToRoute(
            'app_admin_event_event_detail',
            [
                'season' => $event->getSeason(),
                'event'  => $event->getSlug(),
            ]
        );
    }
}
