<?php

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCountNewClientsController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this -> userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $minDateString = $request -> query -> get('min_date');
        $maxDateString = $request -> query -> get('max_date');
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);

        $userEntities = $this -> userRepository -> findNewUserBetweenDates($minDate, $maxDate);

        return $this -> json(count($userEntities));
    }
}
