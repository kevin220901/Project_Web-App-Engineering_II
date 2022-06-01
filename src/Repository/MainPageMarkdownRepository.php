<?php

namespace App\Repository;

use App\Entity\MainPageMarkdown;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MainPageMarkdown>
 *
 * @method MainPageMarkdown|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainPageMarkdown|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainPageMarkdown[]    findAll()
 * @method MainPageMarkdown[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainPageMarkdownRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MainPageMarkdown::class);
    }

    public function add(MainPageMarkdown $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MainPageMarkdown $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MainPageMarkdown[] Returns an array of MainPageMarkdown objects
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

//    public function findOneBySomeField($value): ?MainPageMarkdown
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
