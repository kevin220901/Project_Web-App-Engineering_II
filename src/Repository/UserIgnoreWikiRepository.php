<?php

namespace App\Repository;

use App\Entity\UserIgnoreWiki;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserIgnoreWiki>
 *
 * @method UserIgnoreWiki|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserIgnoreWiki|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserIgnoreWiki[]    findAll()
 * @method UserIgnoreWiki[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserIgnoreWikiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserIgnoreWiki::class);
    }

    public function add(UserIgnoreWiki $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserIgnoreWiki $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserIgnoreWiki[] Returns an array of UserIgnoreWiki objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserIgnoreWiki
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
