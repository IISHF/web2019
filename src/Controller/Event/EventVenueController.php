<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:48
 */

namespace App\Controller\Event;

use App\Application\Event\Command\CreateEventVenue;
use App\Application\Event\Command\DeleteEventVenue;
use App\Application\Event\Command\UpdateEventVenue;
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\EventVenueRepository;
use App\Infrastructure\Event\Form\CreateEventVenueType;
use App\Infrastructure\Event\Form\UpdateEventVenueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventVenueController
 *
 * @package App\Controller\Event
 *
 * @Route("/events/venues")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class EventVenueController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request              $request
     * @param EventVenueRepository $venueRepository
     * @return Response
     */
    public function list(Request $request, EventVenueRepository $venueRepository): Response
    {
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 30);

        return $this->render(
            'event/venue/list.html.twig',
            [
                'venues' => $venueRepository->findPaged($page, $limit),
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
        $createVenue = CreateEventVenue::create();
        $form        = $this->createForm(CreateEventVenueType::class, $createVenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($createVenue);
            $this->addFlash('success', 'The new venue has been created.');

            return $this->redirectToRoute('app_event_eventvenue_list');
        }

        return $this->render(
            'event/venue/create.html.twig',
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
     *      class="App\Domain\Model\Event\EventVenue",
     *      converter="app.event_venue"
     * )
     *
     * @param EventVenue $venue
     * @return Response
     */
    public function detail(EventVenue $venue): Response
    {
        return $this->render(
            'event/venue/detail.html.twig',
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
     *      class="App\Domain\Model\Event\EventVenue",
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
        $updateVenue = UpdateEventVenue::update($venue);
        $form        = $this->createForm(UpdateEventVenueType::class, $updateVenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($updateVenue);
            $this->addFlash('success', 'The venue has been updated.');

            return $this->redirectToRoute('app_event_eventvenue_list');
        }

        return $this->render(
            'event/venue/update.html.twig',
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
     *      class="App\Domain\Model\Event\EventVenue",
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
        $deleteVenue = DeleteEventVenue::delete($venue);

        if (!$this->isCsrfTokenValid('venue_delete_' . $venue->getId(), $request->request->get('_token'))) {
            throw new BadRequestHttpException();
        }

        $commandBus->dispatch($deleteVenue);
        $this->addFlash('success', 'The venue has been deleted.');

        return $this->redirectToRoute('app_event_eventvenue_list');
    }
}
