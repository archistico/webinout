<?php

namespace App\Repository;

use App\Entity\Meso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meso>
 *
 * @method Meso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meso[]    findAll()
 * @method Meso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meso::class);
    }

    /**
     * @return Meso[] Returns an array of Meso objects
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('meso')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->orderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Meso
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
