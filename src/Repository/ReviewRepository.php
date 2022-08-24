<?php

namespace App\Repository;

use App\Entity\Products;
use App\Entity\ReviewsProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use http\Message;

/**
 * @extends ServiceEntityRepository<ReviewsProduct>
 *
 * @method ReviewsProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewsProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewsProduct[]    findAll()
 * @method ReviewsProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewsProduct::class);
    }

    public function add(ReviewsProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReviewsProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //SELECT AVG(note) FROM reviews_product WHERE `product_id` = 1;
    public function countAverageProduct(Products $products) {

        return $this->createQueryBuilder('a')
            ->select('AVG(a.note)')
            ->andWhere('a.product = :product')
            ->setParameter('product', $products)
            ->getQuery()->getSingleScalarResult()
            ;
    }

//    /**
//     * @return ReviewsProduct[] Returns an array of ReviewsProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReviewsProduct
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
