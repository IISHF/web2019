<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-05
 * Time: 13:18
 */

namespace App\Controller\Admin;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class NationalGoverningBodyController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/national-governing-bodies")
 */
class NationalGoverningBodyController
{
    /**
     * @Route("/list", methods={"GET"})
     *
     * @param NationalGoverningBodyRepository $ngbRepository
     * @param SerializerInterface             $serializer
     * @return Response
     */
    public function list(NationalGoverningBodyRepository $ngbRepository, SerializerInterface $serializer): Response
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                array_map(
                    function (NationalGoverningBody $ngb) {
                        return [
                            'id'           => $ngb->getId(),
                            'name'         => $ngb->getName(),
                            'country_name' => $ngb->getCountryName(),
                        ];
                    },
                    $ngbRepository->findAll()
                ),
                'json'
            )
        );
    }
}
