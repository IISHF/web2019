<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin\Event;

use App\Application\Event\Venue\Command\CreateEventVenue;
use App\Application\Event\Venue\Command\DeleteEventVenue;
use App\Application\Event\Venue\Command\UpdateEventVenue;
use App\Domain\Model\Event\Venue\EventVenue;
use App\Domain\Model\Event\Venue\EventVenueRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Controller\PagingRequest;
use App\Infrastructure\Event\Venue\Form\CreateEventVenueType;
use App\Infrastructure\Event\Venue\Form\UpdateEventVenueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventVenueController
 *
 * @package App\Controller\Admin\Event
 *
 * @Route("/admin/events/venues")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class EventVenueController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param Request              $request
     * @param EventVenueRepository $venueRepository
     * @return Response
     */
    public function list(Request $request, EventVenueRepository $venueRepository): Response
    {
        $paging = PagingRequest::create($request);
        return $this->render(
            'admin/event/venue/list.html.twig',
            [
                'venues' => $venueRepository->findPaged($paging->getPage(), $paging->getLimit()),
            ]
        );
    }

    /**
     * @Route("/create", methods={"GET", "POST"})
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        $create = CreateEventVenue::create();
        $form   = $this->createForm(CreateEventVenueType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new venue has been created.');

            return $this->redirectToRoute('app_admin_event_eventvenue_list');
        }

        return $this->render(
            'admin/event/venue/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *      "/{venue}",
     *      methods={"GET"},
     *     requirements={"venue": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="venue",
     *      class="App\Domain\Model\Event\Venue\EventVenue",
     *      converter="app.event_venue"
     * )
     *
     * @param EventVenue $venue
     * @return Response
     */
    public function detail(EventVenue $venue): Response
    {
        return $this->render(
            'admin/event/venue/detail.html.twig',
            [
                'venue' => $venue,
            ]
        );
    }

    /**
     * @Route(
     *     "/{venue}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"venue": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="venue",
     *      class="App\Domain\Model\Event\Venue\EventVenue",
     *      converter="app.event_venue"
     * )
     *
     * @param Request             $request
     * @param EventVenue          $venue
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, EventVenue $venue, MessageBusInterface $commandBus): Response
    {
        $update = UpdateEventVenue::update($venue);
        $form   = $this->createForm(UpdateEventVenueType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The venue has been updated.');

            return $this->redirectToRoute('app_admin_event_eventvenue_list');
        }

        return $this->render(
            'admin/event/venue/update.html.twig',
            [
                'venue' => $venue,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{venue}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"venue": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="venue",
     *      class="App\Domain\Model\Event\Venue\EventVenue",
     *      converter="app.event_venue"
     * )
     *
     * @param Request             $request
     * @param EventVenue          $venue
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function delete(Request $request, EventVenue $venue, MessageBusInterface $commandBus): Response
    {
        $delete = DeleteEventVenue::delete($venue);

        $this->handleCsrfCommand($delete, 'venue_delete_' . $venue->getId(), $request, $commandBus);

        $this->addFlash('success', 'The venue has been deleted.');

        return $this->redirectToRoute('app_admin_event_eventvenue_list');
    }
}
