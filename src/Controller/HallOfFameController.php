<?php
/*
 * Copyright (c) 2021 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Controller;

use App\Domain\Model\HallOfFame\HallOfFameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IISHFController
 *
 * @package App\Controller
 *
 * @Route("/history")
 */
class HallOfFameController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param HallOfFameRepository $hallOfFameRepository
     * @return Response
     */
    public function index(HallOfFameRepository $hallOfFameRepository): Response
    {
        $byYear = [];
        foreach ($hallOfFameRepository->findAll() as $event) {
            $byYear[$event->getSeason()][] = $event;
        }
        return $this->render(
            'hall_of_game/index.html.twig',
            [
                'eventsByYear' => $byYear,
            ]
        );
    }
}
