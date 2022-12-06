<?php

namespace App\Repository;

use App\Entity\ShopProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopProduct>
 *
 * @method ShopProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopProduct[]    findAll()
 * @method ShopProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopProduct::class);
    }

    public function save(ShopProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShopProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @throws NonUniqueResultException
     */
    public function findOneBySomeField(int $shop_id): ?ShopProduct
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.shop = :val')
            ->setParameter('val', $shop_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
