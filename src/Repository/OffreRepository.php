<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @extends ServiceEntityRepository<Offre>
 */
class OffreRepository extends ServiceEntityRepository
{

    public const OFFRES_PER_PAGE = 6;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function searchOffre($term)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.Auteur', 'u')
            ->where('o.titre LIKE :term')
            ->orWhere('o.description LIKE :term')
            ->orWhere('o.Lieu LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }

    public function getOffresPaginator(int $offset) 
    {
        $query = $this->createQueryBuilder('o')
            ->orderBy('o.date_modification', 'DESC')
            ->setMaxResults(self::OFFRES_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }

    public function getOffresPaginatorAsc(int $offset) 
    {
        $query = $this->createQueryBuilder('o')
            ->orderBy('o.date_modification', 'ASC')
            ->setMaxResults(self::OFFRES_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }
    //    /**
    //     * @return Offre[] Returns an array of Offre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Offre
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
