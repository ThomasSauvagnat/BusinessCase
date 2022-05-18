<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    //Total de produits vendus triés par ordre décroissant (Le produit le plus vendu sera en tête de liste, afficher le nombre d’unités vendues pour chaque produit)
    public function getTotalProducts($minDate, $maxDate)
    {
        return $this->createQueryBuilder('p')
            ->join('p.commands', 'c')
            -> where('c.createdAt > :date_min')
            -> andWhere('c.createdAt < :date_max')
            -> andWhere('c.status = 200 OR c.status = 300')
            -> setParameter('date_min', $minDate)
            -> setParameter('date_max', $maxDate)
            ->groupBy('p.label')
            ->orderBy('c.totalPrice')
            ->getQuery()->getResult();
    }

    // Meilleurs ventes (4)
    public function getBestSells()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('COUNT(c) as countedCommand')
            ->join('p.commands', 'c')
            ->orderBy('countedCommand', 'DESC')
            ->groupBy('p.id')
            ->setMaxResults(4)
            ->getQuery()->getResult();
    }

    public function getLastProducts()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()->getResult();
    }

    // Récupération de tous les produits concernant les chiens
    public function getAllProductsDog()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 1 OR c.id = 4 OR c.id = 5 OR c.id = 7')
            ->getQuery()->getResult();
    }

    // Récupération de tous les produits concernant les chats
    public function getAllProductsCat()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 3 OR c.id = 6 OR c.id = 8')
            ->getQuery()->getResult();
    }

    // Récupération des produits nourriture pour chien
    public function getFoodProductsDog()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 4')
            ->andWhere('c.categoryParent = 1')
            ->getQuery()->getResult();
    }

    // Récupération des accessoires pour chien
    public function getAccessoriesProductsDog()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 5')
            ->andWhere('c.categoryParent = 1')
            ->getQuery()->getResult();
    }

    // Récupération des nourritures pour chats
    public function getFoodProductsCat()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 3')
            ->andWhere('c.categoryParent = 2')
            ->getQuery()->getResult();
    }

    // Récupération des accessoires pour chats
    public function getAccessoriesProductsCat()
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.id = 6')
            ->andWhere('c.categoryParent = 2')
            ->getQuery()->getResult();
    }
}
