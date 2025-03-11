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

    /**
    * @return MovimentoRicorrente[] Per la lista dei movimenti ricorrenti
    */
    public function lista(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->orderBy('m.Inizio', 'DESC')
            ->addOrderBy('m.Fine', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRicorrenti(\DateTime $oggi): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.Attivo = :attivo')
            ->andWhere('r.Inizio <= :oggi')
            ->andWhere('r.Fine IS NULL OR r.Fine >= :oggi')
            ->setParameter('attivo', true)
            ->setParameter('oggi', $oggi)
            ->setParameter('giornoPagamento', $oggi->format('j'))
            ->getQuery()
            ->getResult();
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
