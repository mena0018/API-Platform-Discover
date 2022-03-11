<?php

namespace App\Repository;

use App\Entity\Bookmark;
use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function updateRateAverage(int $bookmarkId)
    {
        $moyenne = $this->getEntityManager()->getRepository(Rating::class)->createQueryBuilder('r')
            ->select('avg(r.value)')
            ->from('Rating', 'r')
            ->getQuery()
            ->getResult()
        ;

        return $this->createQueryBuilder('b')
            ->update('Bookmark', 'b')
            ->set('b.rateAverage',  $moyenne)
            ->where('b.id = :bookmarkId')
            ->setParameter('bookmarkId', $bookmarkId)
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return Bookmark[] Returns an array of Bookmark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bookmark
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
