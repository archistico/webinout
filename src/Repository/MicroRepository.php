<?php

namespace App\Repository;

use App\Entity\Micro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Micro>
 *
 * @method Micro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Micro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Micro[]    findAll()
 * @method Micro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Micro::class);
    }

//    /**
//     * @return Micro[] Returns an array of Micro objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Micro
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
