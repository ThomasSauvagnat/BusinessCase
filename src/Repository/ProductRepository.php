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

}
