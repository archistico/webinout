<?php

namespace App\Repository;

use App\Entity\Macro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Macro>
 *
 * @method Macro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Macro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Macro[]    findAll()
 * @method Macro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MacroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Macro::class);
    }

//    /**
//     * @return Macro[] Returns an array of Macro objects
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

//    public function findOneBySomeField($value): ?Macro
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
