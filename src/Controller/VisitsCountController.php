<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitsCountController extends AbstractController
{
    private VisitRepository $visitRepository;
    public function __construct(VisitRepository $visitRepository)
    {
        $this -> visitRepository = $visitRepository;
    }

    public function __invoke(Request $request)
    {
        $minDateString = $request -> query -> get('min_date');
        $maxDateString = $request -> query -> get('max_date');

        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);

        $visitsNumber = $this -> visitRepository -> findVisitsBetweenDates($minDate, $maxDate);

        return $this -> json(count($visitsNumber));
    }
}
