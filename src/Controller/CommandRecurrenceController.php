<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandRecurrenceController extends AbstractController
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

        $newUserEntities = $this -> commandRepository -> findUserCommandsBetweenDates($minDate, $maxDate);
        $ancientUserEntities = $this -> commandRepository -> findUserCommandsBeforeDate($minDate, $maxDate);

        dump($newUserEntities);
        dump($ancientUserEntities);

        if (count($newUserEntities) != 0) {
            $recurrence = ( (count($newUserEntities) - count($ancientUserEntities))/ count($newUserEntities) )* 100;
            return $this -> json(['result' => $recurrence]);
        }

        $error = 0;
        return $this -> json(['result' => $error]);
        
    }
}
