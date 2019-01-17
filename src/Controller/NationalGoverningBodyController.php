<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 17:42
 */

namespace App\Controller;

use App\Application\NationalGoverningBody\Command\CreateNationalGoverningBody;
use App\Application\NationalGoverningBody\Command\DeleteNationalGoverningBody;
use App\Application\NationalGoverningBody\Command\UpdateNationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\NationalGoverningBody\Form\CreateNationalGoverningBodyType;
use App\Infrastructure\NationalGoverningBody\Form\UpdateNationalGoverningBodyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NationalGoverningBodyController
 *
 * @package App\Controller
 *
 * @Route("/national-governing-bodies")
 */
class NationalGoverningBodyController extends AbstractController
{
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param NationalGoverningBodyRepository $ngbRepository
     * @return Response
     */
    public function list(NationalGoverningBodyRepository $ngbRepository): Response
    {
        return $this->render(
            'national_governing_body/list.html.twig',
            [
                'ngbs' => $ngbRepository->findAll(),
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
        $createNgb = CreateNationalGoverningBody::create();
        $form      = $this->createForm(CreateNationalGoverningBodyType::class, $createNgb);

        if ($this->handleForm($createNgb, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new national governing body has been created.');

            return $this->redirectToRoute('app_nationalgoverningbody_list');
        }

        return $this->render(
            'national_governing_body/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{ngb}",
     *     methods={"GET"},
     *     requirements={"ngb": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     *
     * @param NationalGoverningBody $ngb
     * @return Response
     */
    public function detail(NationalGoverningBody $ngb): Response
    {
        return $this->render(
            'national_governing_body/detail.html.twig',
            [
                'ngb' => $ngb,
            ]
        );
    }

    /**
     * @Route(
     *     "/{ngb}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"ngb": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     * @Security("is_granted('NATIONAL_GOVERNING_BODY_EDIT', ngb)")
     *
     * @param Request               $request
     * @param NationalGoverningBody $ngb
     * @param MessageBusInterface   $commandBus
     * @return Response
     */
    public function update(Request $request, NationalGoverningBody $ngb, MessageBusInterface $commandBus): Response
    {
        $updateNgb = UpdateNationalGoverningBody::update($ngb);
        $form      = $this->createForm(UpdateNationalGoverningBodyType::class, $updateNgb);

        if ($this->handleForm($updateNgb, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The national governing body has been updated.');

            return $this->redirectToRoute('app_nationalgoverningbody_list');
        }

        return $this->render(
            'national_governing_body/update.html.twig',
            [
                'ngb'  => $ngb,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{ngb}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"ngb": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     * @Security("is_granted('NATIONAL_GOVERNING_BODY_DELETE', ngb)")
     *
     * @param Request               $request
     * @param NationalGoverningBody $ngb
     * @param MessageBusInterface   $commandBus
     * @return Response
     */
    public function delete(Request $request, NationalGoverningBody $ngb, MessageBusInterface $commandBus): Response
    {
        $deleteNgb = DeleteNationalGoverningBody::delete($ngb);

        $this->handleCsrfCommand($deleteNgb, 'ngb_delete_' . $ngb->getId(), $request, $commandBus);

        $this->addFlash('success', 'The national governing body has been deleted.');

        return $this->redirectToRoute('app_nationalgoverningbody_list');
    }
}
