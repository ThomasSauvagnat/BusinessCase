<?php

namespace App\Controller;

use App\Repository\ProductRepository;
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

    #[Route('/basket/ajout/{id}', name: 'app_basket_add')]
    public function addProduct($id, BasketService $basketService, ProductRepository $productRepository): Response
    {
        // Récupération de l'utilisateur
        $userEntity = $this->getUser();
        // Récupération du produit par son ID
        $productEntity = $productRepository->find($id);
        // Ajout du produit dans le panier de l'utilisateur
        $basketUser = $basketService->addProductToBasket($productEntity, $userEntity);
        dump($basketUser);

        return $this->redirectToRoute('home');
    }

    #[Route('/basket/Supprimer/{id}', name: 'app_basket_remove')]
    public function removeProduct($id, BasketService $basketService, ProductRepository $productRepository): Response
    {
        $userEntity = $this->getUser();
        $productEntity = $productRepository->find($id);
        $basketUser = $basketService->removeProductFromBasket($productEntity, $userEntity);
        dump($basketUser);

        return $this->redirectToRoute('app_basket');
    }
}
