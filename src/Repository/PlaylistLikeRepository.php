<?php

namespace App\Repository;

use App\Entity\PlaylistLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlaylistLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlaylistLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlaylistLike[]    findAll()
 * @method PlaylistLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaylistLike::class);
    }

    public function findByUser($playlist, $user)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.playlist = :playlist')
            ->andWhere('p._user = :user')
            ->setParameter('playlist', $playlist)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    public function findCount($playlistId)
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.playlistId = :playlistId')
            ->setParameter('playlistId', $playlistId)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    // /**
    //  * @return PlaylistLike[] Returns an array of PlaylistLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlaylistLike
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
