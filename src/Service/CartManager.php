<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ProductCartRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em, CartRepository $cartRepository, ProductCartRepository $productCartRepository)
	{
		$this->em = $em;
		$this->cartRepository = $cartRepository;
        $this->productCartRepository = $productCartRepository;
	}

	public function deleteExpiredCarts(): void
	{
		$carts = $this->cartRepository->findAll();
		foreach ($carts as $cart) {
            $dateCart = $cart->getCreatedAt()->format("Y-m-d H:i:s");
            $diff = date_diff(date_create(), date_create($dateCart))->d;
            if($diff > 7) {
                $cartProducts = $cart->getProductCarts();

                if(count($cartProducts) > 0) {
                    for ($i=0; $i < count($cartProducts); $i++) { 
                        $entity = $cartProducts[$i];
                        $this->productCartRepository->remove($entity, true);
                    }
                }
        
                $this->cartRepository->remove($cart, true);
            }
		}
	}

}
