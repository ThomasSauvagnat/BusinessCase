<?php

namespace App\Controller;

use App\services\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(BasketService $basketService): Response
    {
        $user = $this->getUser();
        $basketUser = $basketService->getBasket($user);
        dump($basketUser);

        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
            'basketUser' => $basketUser
        ]);
    }
}
