<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $user = $this->getUser();
        dump($user);
        $userCommands = $user->getCommands();
        $userAdresses = $user->getAdresses();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'userCommands' => $userCommands,
            'userAdresses' => $userAdresses,
        ]);
    }
}
