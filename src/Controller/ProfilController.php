<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/profil/{id}/edit', name: 'app_profil_edit')]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form-> isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('admin_profil/_edit.html.twig', [
            'user' => $user,
            'form' => $form ->createView(),
        ]);
    }
}
