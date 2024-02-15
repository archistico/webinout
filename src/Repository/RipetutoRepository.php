<?php

namespace App\Repository;

use App\Entity\Ripetuto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ripetuto>
 *
 * @method Ripetuto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ripetuto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ripetuto[]    findAll()
 * @method Ripetuto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RipetutoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ripetuto::class);
    }

//    /**
//     * @return Ripetuto[] Returns an array of Ripetuto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ripetuto
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
