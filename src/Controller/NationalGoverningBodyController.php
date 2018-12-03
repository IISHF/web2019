<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 17:42
 */

namespace App\Controller;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NationalGoverningBodyController
 *
 * @package App\Controller
 */
class NationalGoverningBodyController extends AbstractController
{
    /**
     * @Route("/national-governing-bodies", methods={"GET"})
     *
     * @param NationalGoverningBodyRepository $repository
     * @return Response
     */
    public function getList(NationalGoverningBodyRepository $repository): Response
    {
        return $this->render(
            'national_governing_body/list.html.twig',
            [
                'ngbs' => $repository->findAll(),
            ]
        );
    }

    /**
     * @Route("/national-governing-bodies/{ngb<[0-9a-z-]+>}", methods={"GET"})
     * @ParamConverter(
     *      name="ngb",
     *      class="App\Domain\Model\NationalGoverningBody\NationalGoverningBody",
     *      converter="app.national_governing_body"
     * )
     *
     * @param NationalGoverningBody $ngb
     * @return Response
     */
    public function getDetail(NationalGoverningBody $ngb): Response
    {
        return $this->render(
            'national_governing_body/detail.html.twig',
            [
                'ngb' => $ngb,
            ]
        );
    }
}
