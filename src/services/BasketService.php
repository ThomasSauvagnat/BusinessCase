<?php

namespace App\services;

use App\Entity\Command;
use App\Entity\User;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;

class BasketService
{
    private CommandRepository $commandRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CommandRepository $commandRepository, EntityManagerInterface $entityManager)
    {
        $this->commandRepository = $commandRepository;
        $this->entityManager = $entityManager;
    }

    function getBasket(User $user) {
        $basketEntity = $this->commandRepository->getBasketByUser($user);
        // Si l'utilisateur n'a pas de panier, on en crée un lié à celui-ci
        if($basketEntity === null) {
            $basketEntity = new Command();
            $basketEntity->setCreatedAt(new \DateTime());
            // On lie le panier à l'utilisateur
            $basketEntity->setUser($user);
            $basketEntity->setStatus(100);
            $basketEntity->setTotalPrice(0);
            $basketEntity->setNumCommand(uniqid());
            $this->entityManager->persist($basketEntity);
            $this->entityManager->flush();
        }
        return $basketEntity;
    }

    function addProductToBasket($productEntity, $user)
    {
        $basketEntity = $this->getBasket($user);
        $basketEntity->addProduct($productEntity);
        $this->entityManager->persist($basketEntity);
        $this->entityManager->flush();
    }

    function removeProductFromBasket($productEntity, $user)
    {
        $basketEntity = $this->getBasket($user);
        $basketEntity->removeProduct($productEntity);
        $this->entityManager->remove($basketEntity);
        $this->entityManager->flush();
    }
}
