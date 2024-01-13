<?php

namespace App\Repository;

use App\Entity\Scadenza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scadenza>
 *
 * @method Scadenza|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scadenza|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scadenza[]    findAll()
 * @method Scadenza[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScadenzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scadenza::class);
    }

    /**
     * @return Scadenza[] Returns an array of Scadenza objects
     */
    public function lista(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.DataScadenza', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Scadenza[] Returns an array of Scadenza objects
     */
    public function listaUltime(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.DataScadenza', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Scadenza
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
