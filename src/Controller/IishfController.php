<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 16:56
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IishfController
 *
 * @package App\Controller
 *
 * @Route("/iishf")
 */
class IishfController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return Response
     */
    public function info(): Response
    {
        return $this->render(
            'iishf/info.html.twig',
            [
            ]
        );
    }
}
