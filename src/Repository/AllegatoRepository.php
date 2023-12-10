<?php

namespace App\Repository;

use App\Entity\Allegato;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Allegato>
 *
 * @method Allegato|null find($id, $lockMode = null, $lockVersion = null)
 * @method Allegato|null findOneBy(array $criteria, array $orderBy = null)
 * @method Allegato[]    findAll()
 * @method Allegato[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegatoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Allegato::class);
    }

//    /**
//     * @return Allegato[] Returns an array of Allegato objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Allegato
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
