<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function save(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProduct(Cart $entity, Product $product): ?ProductCart
    {
        $isProduct = null;
        $cartProducts = $entity->getProductCarts();
        for($i = 0; $i < count($cartProducts); $i++) {
            if($cartProducts[$i]->getProduct()->getId() == $product->getId()) {
                $isProduct = $cartProducts[$i];
                break;
            }
        }
        return $isProduct;
    }

    public function getTotalAmount(Cart $entity): int
    {
        $cartProducts = $entity->getProductCarts();
        $total = 0;
        for($i = 0; $i < count($cartProducts); $i++) {
            $product = $cartProducts[$i]->getProduct();
            $total += $cartProducts[$i]->getQuantity() * $product->getPrice();
        }
        return $total;
    }

//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
