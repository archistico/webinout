<?php

namespace App\Repository;

use App\Entity\Eseguito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eseguito>
 *
 * @method Eseguito|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eseguito|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eseguito[]    findAll()
 * @method Eseguito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EseguitoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eseguito::class);
    }

//    /**
//     * @return Eseguito[] Returns an array of Eseguito objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eseguito
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
