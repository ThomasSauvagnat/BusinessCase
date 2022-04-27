<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandBasketsAverageController extends AbstractController
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

        $commandEntities = $this -> commandRepository -> findTotalBasketsBetweenDates($minDate, $maxDate);

        $total = 0;
        foreach ($commandEntities as $id => $command) {
            $total += $command -> getTotalPrice();
        }

        $basket_average = $total / count($commandEntities);

        return $this ->json ($basket_average);
    }
}
