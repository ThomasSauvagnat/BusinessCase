<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandTotalController extends AbstractController
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

        $commandEntities = $this -> commandRepository -> findTotalCommandsBetweenDates($minDate, $maxDate);
        
        dump($commandEntities);


        return $this -> json(['result' => count($commandEntities)]);
    }
}
