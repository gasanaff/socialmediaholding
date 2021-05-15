<?php

namespace App\Repository;

use App\Entity\TrackLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrackLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrackLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrackLike[]    findAll()
 * @method TrackLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrackLike::class);
    }

    public function findByUser($track, $user)
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andWhere('t.track = :track')
            ->andWhere('t._user = :user')
            ->setParameter('track', $track)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    public function findCount($track)
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andWhere('t.track = :track')
            ->setParameter('track', $track)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }
    // /**
    //  * @return TrackLike[] Returns an array of TrackLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrackLike
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
