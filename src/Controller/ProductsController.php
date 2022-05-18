<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    #[Route('/produits/chiens', name: 'app_products_dog')]
    public function index(): Response
    {
        // Tous les produits chiens
        $dogProducts = $this->productRepository->getAllProductsDog();

        return $this->render('products/index.html.twig', [
            'dogProducts' => $dogProducts,
        ]);
    }

    #[Route('/produits/chats', name: 'app_products_cat')]
    public function catProducts(): Response
    {
        // Tous les produits chats
        $catProducts = $this->productRepository->getAllProductsCat();

        return $this->render('products/allProductsCat.html.twig', [
            'catProducts' => $catProducts
        ]);
    }

    #[Route('/produits/nourriture/chiens', name: 'app_products_food_dog')]
    public function foodDogProduct(): Response
    {
        // Produits nourriture pour chiens
        $dogFoodProducts = $this->productRepository->getFoodProductsDog();

        return $this->render('products/dogFoodProducts.html.twig', [
            'dogFoodProducts' => $dogFoodProducts
        ]);
    }

    #[Route('/produits/accessoires/chiens', name: 'app_products_accessories_dog')]
    public function accessoriesDogProduct(): Response
    {
        // Accessoires pour chiens
        $dogAccessoriesProducts = $this->productRepository->getAccessoriesProductsDog();

        return $this->render('products/dogAccessoriesProducts.html.twig', [
            'dogAccessoriesProducts' => $dogAccessoriesProducts
        ]);
    }

    #[Route('/produits/nourriture/chats', name: 'app_products_food_cat')]
    public function catFoodProducts(): Response
    {
        // Tous les produits chats
        $catFoodProducts = $this->productRepository->getFoodProductsCat();

        return $this->render('products/catFoodProducts.html.twig', [
            'catFoodProducts' => $catFoodProducts
        ]);
    }

    #[Route('/produits/accessoires/chats', name: 'app_products_accessories_cat')]
    public function catAccessoriesProducts(): Response
    {
        // Tous les accessoires chats
        $catAccessoriesProducts = $this->productRepository->getAccessoriesProductsCat();

        return $this->render('products/catAccessoriesProducts.html.twig', [
            'catAccessoriesProducts' => $catAccessoriesProducts
        ]);
    }
}
