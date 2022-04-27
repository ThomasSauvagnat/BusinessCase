<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Command>
 *
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Command $entity, bool $flush = true): void
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
    public function remove(Command $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // Total de vente entre 2 dates
    public function findTotalSellsBetweenDates($minDate, $maxDate)
    {
        return $this -> createQueryBuilder('c')
            -> where('c.createdAt > :date_min')
            -> andWhere('c.createdAt < :date_max')
            -> andWhere('c.status = 200 OR c.status = 300')
            -> setParameter('date_min', $minDate)
            -> setParameter('date_max', $maxDate)
            -> getQuery() -> getResult();
    }

    // Total de paniers entre 2 dates
    public function findTotalBasketsBetweenDates($minDate, $maxDate)
    {
        return $this -> createQueryBuilder('c')
            -> where('c.createdAt > :date_min')
            -> andWhere('c.createdAt < :date_max')
            -> andWhere('c.status = 100')
            -> setParameter('date_min', $minDate)
            -> setParameter('date_max', $maxDate)
            -> getQuery() -> getResult();
    }

    // Total de commandes entre 2 dates
    public function findTotalCommandsBetweenDates($minDate, $maxDate)
    {
        return $this -> createQueryBuilder('c')
            -> where('c.createdAt > :date_min')
            -> andWhere('c.createdAt < :date_max')
            -> andWhere('c.status = 200 OR c.status = 300 OR c.status = 400 OR c.status = 500')
            -> setParameter('date_min', $minDate)
            -> setParameter('date_max', $maxDate)
            -> getQuery() -> getResult();
    }

    public function test()
    {
        return $this -> createQueryBuilder('c')
            // Lors d'un innerJoin :
            // 1er argument la table que l'on veut joindre: command.user
            // 2e argument c'est son alias (qu'on lui attribut) : 'u'
            -> innerJoin('c.u', 'u');
    }

    // /**
    //  * @return Command[] Returns an array of Command objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
