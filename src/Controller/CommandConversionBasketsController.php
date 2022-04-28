<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandConversionBasketsController extends AbstractController
{
    private CommandRepository $commandRepository;
    private VisitRepository $visitRepository;

    public function __construct(CommandRepository $commandRepository, VisitRepository $visitRepository)
    {
        $this -> commandRepository = $commandRepository;
        $this -> visitRepository = $visitRepository;
    }

    public function __invoke(Request $request)
    {
        $minDateString = $request -> query -> get('min_date');
        $maxDateString = $request -> query -> get('max_date');
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);

        $commandEntities = $this -> commandRepository -> findTotalBasketsBetweenDates($minDate, $maxDate);
        $visitEntities = $this -> visitRepository -> findVisitsBetweenDates($minDate, $maxDate);

        $nb_baskests = count($commandEntities);
        $nb_visits = count($visitEntities);
        // dump($nb_baskests);
        // dump($nb_visits);

        $basket_conversion = ($nb_baskests / $nb_visits) * 100;

        return $this -> json(['result' => $basket_conversion]);
    }
}
