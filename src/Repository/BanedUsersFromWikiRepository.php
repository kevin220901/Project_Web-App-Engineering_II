<?php

namespace App\Repository;

use App\Entity\BanedUsersFromWiki;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BanedUsersFromWiki>
 *
 * @method BanedUsersFromWiki|null find($id, $lockMode = null, $lockVersion = null)
 * @method BanedUsersFromWiki|null findOneBy(array $criteria, array $orderBy = null)
 * @method BanedUsersFromWiki[]    findAll()
 * @method BanedUsersFromWiki[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanedUsersFromWikiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BanedUsersFromWiki::class);
    }

    public function add(BanedUsersFromWiki $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BanedUsersFromWiki $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BanedUsersFromWiki[] Returns an array of BanedUsersFromWiki objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BanedUsersFromWiki
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
