<?php

namespace App\Twig;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProductExtension extends AbstractExtension
{
    // Fait appel au product repository pour l'utiliser dans notre fonction
    // Ajout de l'objet Environnement => permet d'utiliser la méthode render qui retourne un template
    public function __construct(private ProductRepository $productRepository, private Environment $environment) {

    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    // Création d'une fonction qui retourne les notes d'un produit
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_stars_for_product', [$this, 'getStarsForProduct']),
        ];
    }

    public function getStarsForProduct(Product $product) {
        $totalNote = 0;
        $sumNote = 0;

        // Boucle pour récupérer les reviews d'un produit (on trouve les notes dans les reviews)
        foreach ($product->getReviews() as $review) {

            // Verifie que les notes ne soient pas null
            if ($review->getNote() !== null) {
                $totalNote++;
                $sumNote += $review->getNote();
            }
            if ($totalNote === 0) {
                return '<p>Aucune note</p>';
            } else {
                // Calcul la somme
                $noteAvg = $sumNote / $totalNote;

                // Retourne notre template product/_stars.html.twig + on passe à notre template une variable 'note' (même principe que dans le controller)
                $html = $this->environment->render('products/_stars.html.twig', [
                   'note' => (int)$noteAvg
                ]);

                // Retourne la variable html => celle-ci sera retourné lorsqu'on utilise la fonction dans notre fichier _cardProduct.html.twig
                return $html;
            }
        }
    }
}
