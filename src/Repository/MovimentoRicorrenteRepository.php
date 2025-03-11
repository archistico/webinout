<?php

namespace App\Repository;

use App\Entity\MovimentoRicorrente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovimentoRicorrente>
 *
 * @method MovimentoRicorrente|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimentoRicorrente|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimentoRicorrente[]    findAll()
 * @method MovimentoRicorrente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimentoRicorrenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimentoRicorrente::class);
    }

//    /**
//     * @return MovimentoRicorrente[] Returns an array of MovimentoRicorrente objects
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

//    public function findOneBySomeField($value): ?MovimentoRicorrente
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
