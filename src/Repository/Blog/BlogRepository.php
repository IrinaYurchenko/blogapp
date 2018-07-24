<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @param string $title
     * @param int $userId
     * @return Blog
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findBlogByUserIdAndTitle(string $title, int $userId)
    {
        $query = $this->createQueryBuilder('b')
            ->where('b.title = :title')
            ->innerJoin('b.user', 'bu', 'WITH', 'bu.id = :id')
            ->setParameter('title', $title)
            ->setParameter('id', $userId)
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    public function findBlogByUserId(int $userId, int $blogId)
    {
        $query = $this->createQueryBuilder('b')
            ->where('b.id = :blogId')
            ->innerJoin('b.user', 'bu', 'WITH', 'bu.id = :id')
            ->setParameter('id', $userId)
            ->setParameter('blogId', $blogId)
            ->getQuery();
        return $query->getOneOrNullResult();

    }
    public function save(Blog $blog)
    {
        $this->getEntityManager()->persist($blog);
        $this->getEntityManager()->flush();
        return $blog->getId();
    }

    public function remove(Blog $blog)
    {
        $posts = $blog->getPosts();
        $em = $this->getEntityManager();
        foreach ($posts as $post) {
            $em->remove($post);
        }

        $em->remove($blog);
        $em->flush();
    }
}
