<?php

namespace App\Repository;

use App\Entity\Movimento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movimento>
 *
 * @method Movimento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movimento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movimento[]    findAll()
 * @method Movimento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movimento::class);
    }

    /**
    * @return Movimento[] Per la lista dei movimenti
    */
    public function lista(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->orderBy('m.Data', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Movimento[] Per la lista dei movimenti
    */
    public function listaUltimiMovimenti($limit): array
    {
        return $this
            ->createQueryBuilder('m')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->orderBy('m.Data', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function SommaImporti(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->select('micro.Nome As MicroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('macro.Nome As MacroNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->groupBy('micro.id')
            ->addGroupBy('meso.id')
            ->addGroupBy('macro.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function SommaImportiAnni(): array
    {
        $qb = $this->createQueryBuilder('m');
        $query = $qb
            ->addselect('micro.Nome As MicroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('macro.Nome As MacroNome')
            ->addselect($qb->expr()->substring('m.Data', 0, 5). ' AS Anno')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->addGroupBy('Anno')
            ->addgroupBy('micro.id')
            ->addGroupBy('meso.id')
            ->addGroupBy('macro.id')
            ->addOrderBy('Anno')
            ->addOrderBy('macro.Nome')
            ->addOrderBy('meso.Nome')
            ->addOrderBy('micro.Nome')
            ->getQuery();

        return $query
            ->getResult()
        ;
    }

    /**
    * @return Movimento[] Somma lista movimenti per categorie
    */
    public function listaSommaPerCategorie(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->addselect('micro.Nome As MicroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('macro.Nome As MacroNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->orderBy('SUM(m.Importo)', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->addGroupBy('macro.Nome')
            ->addGroupBy('meso.Nome')
            ->addGroupBy('micro.Nome')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Movimento[] Somma lista movimenti per categorie in un periodo
    */
    public function listaSommaPerCategoriePeriodo(\DateTime $inizio, \DateTime $fine): array
    {
        return $this
            ->createQueryBuilder('m')
            ->addselect('micro.Nome As MicroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('macro.Nome As MacroNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->setParameter('inizio', $inizio)
            ->setParameter('fine', $fine)
            ->Where('m.Data >= :inizio')
            ->andWhere('m.Data <= :fine')
            ->orderBy('SUM(m.Importo)', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->addGroupBy('macro.Nome')
            ->addGroupBy('meso.Nome')
            ->addGroupBy('micro.Nome')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Movimento[] Lista movimenti dal
    */
    public function listaPrevisione(\DateTime $datainizio, int $mesi): array
    {
        return $this
            ->createQueryBuilder('m')
            ->setParameter('mesi', $mesi)
            ->addselect('macro.Nome As MacroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('micro.Nome As MicroNome')
            ->addSelect('SUM(m.Importo)/:mesi as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->setParameter('datainizio', $datainizio->format('Y-m-d'))
            ->Where('m.Data >= :datainizio')
            ->GroupBy('macro.Nome')
            ->addGroupBy('meso.Nome')
            ->addGroupBy('micro.Nome')
            ->orderBy('Totale', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')
            ->addOrderBy('meso.Nome', 'ASC')
            ->addOrderBy('micro.Nome', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array 
    */
    public function listaPrevisioneCategoria(\DateTime $datainizio, int $mesi, string $categoria): array
    {
        return $this
            ->createQueryBuilder('m')
            ->setParameter('mesi', $mesi)
            ->addselect('macro.Nome As MacroNome')
            ->addSelect('SUM(m.Importo)/:mesi as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->setParameter('datainizio', $datainizio->format('Y-m-d'))
            ->Where('m.Data >= :datainizio')
            ->setParameter('categoria', $categoria)
            ->andWhere('macro.Nome = :categoria')
            ->GroupBy('macro.Nome')
            ->orderBy('Totale', 'DESC')
            ->addOrderBy('macro.Nome', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array 
    */
    public function listaTotaliPerMicro(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->addselect('macro.Nome As MacroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addselect('micro.Nome As MicroNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->GroupBy('macro.id')
            ->addGroupBy('meso.id')
            ->addGroupBy('micro.id')
            ->addOrderBy('macro.Nome', 'ASC')            
            ->addOrderBy('meso.Nome', 'ASC')            
            ->addOrderBy('micro.Nome', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array 
    */
    public function listaTotaliPerMeso(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->addselect('macro.Nome As MacroNome')
            ->addselect('meso.Nome As MesoNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->GroupBy('macro.id')
            ->addGroupBy('meso.id')
            ->addOrderBy('macro.Nome', 'ASC')            
            ->addOrderBy('meso.Nome', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return array 
    */
    public function listaTotaliPerMacro(): array
    {
        return $this
            ->createQueryBuilder('m')
            ->addselect('macro.Nome As MacroNome')
            ->addSelect('SUM(m.Importo) as Totale')
            ->innerJoin('m.Categoria', 'micro', 'WITH', 'm.Categoria = micro.id')
            ->innerJoin('micro.Padre', 'meso', 'WITH', 'micro.Padre = meso.id')
            ->innerJoin('meso.Padre', 'macro', 'WITH', 'meso.Padre = macro.id')
            ->GroupBy('macro.id')
            ->addOrderBy('macro.Nome', 'ASC')            
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Movimento[] Returns an array of Movimento objects
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

//    public function findOneBySomeField($value): ?Movimento
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
