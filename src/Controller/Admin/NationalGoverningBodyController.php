<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller\Admin;

use App\Application\NationalGoverningBody\Command\AddNationalGoverningBodyLogo;
use App\Application\NationalGoverningBody\Command\CreateNationalGoverningBody;
use App\Application\NationalGoverningBody\Command\DeleteNationalGoverningBody;
use App\Application\NationalGoverningBody\Command\RemoveNationalGoverningBodyLogo;
use App\Application\NationalGoverningBody\Command\UpdateNationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Http\ApiResponse;
use App\Infrastructure\NationalGoverningBody\Form\CreateNationalGoverningBodyType;
use App\Infrastructure\NationalGoverningBody\Form\UpdateNationalGoverningBodyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NationalGoverningBodyController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/national-governing-bodies")
 * @Security("is_granted('ROLE_ADMIN')")
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
            'admin/national_governing_body/list.html.twig',
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
        $create = CreateNationalGoverningBody::create();
        $form   = $this->createForm(CreateNationalGoverningBodyType::class, $create);

        if ($this->handleForm($create, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new national governing body has been created.');

            return $this->redirectToRoute('app_admin_nationalgoverningbody_list');
        }

        return $this->render(
            'admin/national_governing_body/create.html.twig',
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
            'admin/national_governing_body/detail.html.twig',
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
        $update = UpdateNationalGoverningBody::update($ngb);
        $form   = $this->createForm(UpdateNationalGoverningBodyType::class, $update);

        if ($this->handleForm($update, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The national governing body has been updated.');

            return $this->redirectToRoute('app_admin_nationalgoverningbody_list');
        }

        return $this->render(
            'admin/national_governing_body/update.html.twig',
            [
                'ngb'  => $ngb,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{ngb}/logo",
     *     methods={"GET"},
     *     requirements={"ngb": "%routing.slug%"}
     * )
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     *
     * @param Request               $request
     * @param NationalGoverningBody $ngb
     * @return Response
     */
    public function logo(Request $request, NationalGoverningBody $ngb): Response
    {
        $logo = $ngb->getLogo();
        if (!$logo) {
            throw $this->createNotFoundException();
        }
        return $this->redirectToRoute(
            'app_file_download',
            [
                'name' => $logo->getName(),
            ],
            Response::HTTP_SEE_OTHER
        );
    }

    /**
     * @Route(
     *     "/{ngb}/logo",
     *     methods={"POST"},
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
    public function addLogo(Request $request, NationalGoverningBody $ngb, MessageBusInterface $commandBus): Response
    {
        $file = $request->files->get('file');
        if (!$file) {
            throw new BadRequestHttpException();
        }
        $addLogo = AddNationalGoverningBodyLogo::addTo($ngb, $file);
        try {
            $commandBus->dispatch($addLogo);
        } catch (ValidationFailedException $e) {
            return ApiResponse::validationFailed($e->getViolations(), $e->getCode());
        }

        return ApiResponse::created(
            'app_admin_nationalgoverningbody_logo',
            [
                'ngb' => $ngb->getSlug(),
            ],
            $this->get('router')
        );
    }

    /**
     * @Route(
     *     "/{ngb}/logo",
     *     methods={"DELETE"},
     *     requirements={"ngb": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     * @Security("is_granted('NATIONAL_GOVERNING_BODY_EDIT', ngb)")
     *
     * @param NationalGoverningBody $ngb
     * @param MessageBusInterface   $commandBus
     * @return Response
     */
    public function removeLogo(NationalGoverningBody $ngb, MessageBusInterface $commandBus): Response
    {
        $removeLogo = RemoveNationalGoverningBodyLogo::removeFrom($ngb);
        $commandBus->dispatch($removeLogo);
        return ApiResponse::noContent();
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
        $delete = DeleteNationalGoverningBody::delete($ngb);

        $this->handleCsrfCommand($delete, 'ngb_delete_' . $ngb->getId(), $request, $commandBus);

        $this->addFlash('success', 'The national governing body has been deleted.');

        return $this->redirectToRoute('app_admin_nationalgoverningbody_list');
    }
}
