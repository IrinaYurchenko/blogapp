<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Analitics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Analitics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Analitics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Analitics[]    findAll()
 * @method Analitics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnaliticsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Analitics::class);
    }

//    /**
//     * @return Analitics[] Returns an array of Analitics objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Analitics
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
