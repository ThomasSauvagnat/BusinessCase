<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandConversionController extends AbstractController
{
    private CommandRepository $commandRepository;

    public function __construct(CommandRepository $commandRepository)
    {
        $this -> commandRepository = $commandRepository;
    }

    public function __invoke(Request $request)
    {
        $minDateString = $request -> query -> get('min_date');
        $maxDateString = $request -> query -> get('max_date');
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);

        $commandBasketsEntities = $this -> commandRepository -> findTotalBasketsBetweenDates($minDate, $maxDate);
        $commandEntities = $this -> commandRepository -> findTotalCommandsBetweenDates($minDate, $maxDate);

        $nb_baskests = count($commandBasketsEntities);
        $nb_commands = count($commandEntities);

        dump($nb_commands);
        dump($nb_baskests);

        $basket_conversion = ($nb_baskests / $nb_commands) * 100;

        return $this -> json($basket_conversion);
    }
}
