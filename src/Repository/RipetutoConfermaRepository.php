<?php

namespace App\Repository;

use App\Entity\RipetutoConferma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RipetutoConferma>
 *
 * @method RipetutoConferma|null find($id, $lockMode = null, $lockVersion = null)
 * @method RipetutoConferma|null findOneBy(array $criteria, array $orderBy = null)
 * @method RipetutoConferma[]    findAll()
 * @method RipetutoConferma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RipetutoConfermaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RipetutoConferma::class);
    }

//    /**
//     * @return RipetutoConferma[] Returns an array of RipetutoConferma objects
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

//    public function findOneBySomeField($value): ?RipetutoConferma
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
