<?php

namespace App\Controller;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MembersController
 *
 * @package App\Controller
 *
 * @Route("/members")
 */
class MembersController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param NationalGoverningBodyRepository $ngbRepository
     * @return Response
     */
    public function index(NationalGoverningBodyRepository $ngbRepository): Response
    {
        return $this->render(
            'members/index.twig',
            [
                'ngbs' => $ngbRepository->findAll(),
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
}
