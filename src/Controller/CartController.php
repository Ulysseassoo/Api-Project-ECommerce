<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class CartController extends AbstractController
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function add_product_cart(Cart $cart, Product $product): Response
    {
        if(!$cart) {
            // TODO Get user connected and create a cart
            return $this->buildNotFoundResponse("This cart doesn't exist.");
        }
        if(!$product) {
            return $this->buildNotFoundResponse("This product doesn't exist.");
        }

        $findProduct = $this->cartRepository->findProduct($cart, $product);

        if($findProduct) {
            $product->setQuantity($product->getQuantity() + 1);
            $this->productRepository->save($product, true);
        } else {
            $cart->addProduct($product);
        }

        $total = $this->cartRepository->getTotalAmount($cart);
        $cart->setTotalAmount($total);
        $this->cartRepository->save($cart, true);


        return $this->buildDataResponse($cart);
    }

}