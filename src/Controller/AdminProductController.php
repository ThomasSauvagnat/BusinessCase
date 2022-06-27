<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPicture;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/admin/produits', name: 'app_admin_products')]
    public function index(): Response
    {
        return $this->render('admin_product/index.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/admin/produit/ajouter', name: 'app_admin_add_product')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Pas besoin d'un get->data() car on est lié a une entité
            // Récupération de l'image
            $image = $form->get('image')->getData();
            if ($image) {
                // Récupération du nom de l'image
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // Sécurisé le nom pour le passage dans l'URL
                $safeFilename = $slugger->slug($originalFilename);
                // Ajoute un unique ID
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('uploadImage'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $productPictureEntity = new ProductPicture();
                $productPictureEntity->setPath('uploads/images/'.$newFilename);
                $productPictureEntity->setLibele($originalFilename);
                $product->addProductPicture($productPictureEntity);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin_product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
