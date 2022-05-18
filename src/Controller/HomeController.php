<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Récupération des 4 meilleurs ventes
        $bestSellsProducts = $this->productRepository->getBestSells();
//        dump($bestSellsProducts);
        // Création d'un tableau pour mettre les produits à l'intérieur (plus facile pour boucler)
        $products = [];
        foreach ($bestSellsProducts as $product) {
            array_push($products, $product[0]);
        }

        // Récupération des 4 nouveautés
        $newProducts = $this->productRepository->getLastProducts();

        return $this->render('home/index.html.twig', [
            'bestSellsProducts' => $products,
            'newProducts' => $newProducts,
        ]);
    }
}
