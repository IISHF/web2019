<?php

namespace App\Controller;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    use RedirectToFileController;

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
     * @param NationalGoverningBody $ngb
     * @return Response
     */
    public function logo(NationalGoverningBody $ngb): Response
    {
        return $this->redirectToFile($ngb->getLogo());
    }
}
