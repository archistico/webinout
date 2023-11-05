<?php

namespace App\Repository;

use App\Entity\TipoPagamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipoPagamento>
 *
 * @method TipoPagamento|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPagamento|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPagamento[]    findAll()
 * @method TipoPagamento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoPagamentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPagamento::class);
    }

//    /**
//     * @return TipoPagamento[] Returns an array of TipoPagamento objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TipoPagamento
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
