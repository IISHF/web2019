<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:48
 */

namespace App\Controller\Event;

use App\Application\Event\Application\Command\ApplyForTitleEvent;
use App\Application\Event\Application\Command\UpdateTitleEventApplication;
use App\Application\Event\Application\Command\WithdrawTitleEventApplication;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\TitleEvent;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Event\Application\Form\ApplyForTitleEventType;
use App\Infrastructure\Event\Application\Form\UpdateTitleEventApplicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TitleEventApplicationController
 *
 * @package App\Controller\Event
 *
 * @Route(
 *      "/events/{event}/applications",
 *      requirements={"event": "%routing.uuid%"}
 * )
 * @ParamConverter(
 *      name="event",
 *      class="App\Domain\Model\Event\Event",
 *      converter="app.event"
 * )
 */
class TitleEventApplicationController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("/apply", methods={"GET", "POST"})
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request             $request
     * @param Event               $event
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function apply(Request $request, Event $event, MessageBusInterface $commandBus): Response
    {
        if (!$event instanceof TitleEvent) {
            throw $this->createNotFoundException();
        }

        $apply = ApplyForTitleEvent::apply($event->getId());
        $form  = $this->createForm(ApplyForTitleEventType::class, $apply);

        if ($this->handleForm($apply, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new title event application has been added.');

            return $this->redirectToRoute(
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/application/apply.html.twig',
            [
                'event' => $event,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{application}",
     *      methods={"GET"},
     *      requirements={"application": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="application",
     *      class="App\Domain\Model\Event\TitleEventApplication",
     *      converter="app.event_application"
     * )
     *
     * @param Event                                                     $event
     * @param \App\Domain\Model\Event\Application\TitleEventApplication $application
     * @return Response
     */
    public function detail(Event $event, \App\Domain\Model\Event\Application\TitleEventApplication $application): Response
    {
        if (!$event instanceof TitleEvent) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'event/application/detail.html.twig',
            [
                'event'       => $event,
                'application' => $application,
            ]
        );
    }

    /**
     * @Route(
     *     "/{application}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"application": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="application",
     *      class="App\Domain\Model\Event\TitleEventApplication",
     *      converter="app.event_application"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request                                                   $request
     * @param Event                                                     $event
     * @param \App\Domain\Model\Event\Application\TitleEventApplication $application
     * @param MessageBusInterface                                       $commandBus
     * @return Response
     */
    public function update(
        Request $request,
        Event $event,
        \App\Domain\Model\Event\Application\TitleEventApplication $application,
        MessageBusInterface $commandBus
    ): Response {
        if (!$event instanceof TitleEvent) {
            throw $this->createNotFoundException();
        }

        $update = UpdateTitleEventApplication::update($application);
        $form   = $this->createForm(UpdateTitleEventApplicationType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The title event application has been updated.');

            return $this->redirectToRoute(
                'app_event_event_detail',
                [
                    'season' => $event->getSeason(),
                    'event'  => $event->getSlug(),
                ]
            );
        }

        return $this->render(
            'event/application/update.html.twig',
            [
                'event'       => $event,
                'application' => $application,
                'form'        => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{application}/withdraw",
     *     methods={"POST", "DELETE"},
     *     requirements={"application": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="application",
     *      class="App\Domain\Model\Event\TitleEventApplication",
     *      converter="app.event_application"
     * )
     * @Security("is_granted('EVENT_EDIT', event)")
     *
     * @param Request                                                   $request
     * @param Event                                                     $event
     * @param \App\Domain\Model\Event\Application\TitleEventApplication $application
     * @param MessageBusInterface                                       $commandBus
     * @return Response
     */
    public function withdraw(
        Request $request,
        Event $event,
        \App\Domain\Model\Event\Application\TitleEventApplication $application,
        MessageBusInterface $commandBus
    ): Response {
        if (!$event instanceof TitleEvent) {
            throw $this->createNotFoundException();
        }

        $withdraw = WithdrawTitleEventApplication::withdraw($application);

        $this->handleCsrfCommand(
            $withdraw,
            'event_application_withdraw_' . $application->getId(),
            $request,
            $commandBus
        );

        $this->addFlash('success', 'The title event application has been withdrawn.');

        return $this->redirectToRoute(
            'app_event_event_detail',
            [
                'season' => $event->getSeason(),
                'event'  => $event->getSlug(),
            ]
        );
    }
}
