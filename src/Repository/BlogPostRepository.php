<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPost>
 *
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function getLatestPosts($limit = null)
    {
        $qb = $this->createQueryBuilder('bp')
            ->select('bp')
            ->addOrderBy('bp.createdAt', 'DESC');

        if ($limit !== null)
            $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult();
    }

    public function save(BlogPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlogPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('bp')
            ->andWhere('bp.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('bp.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?BlogPost
    {
        return $this->createQueryBuilder('bp')
            ->andWhere('bp.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
