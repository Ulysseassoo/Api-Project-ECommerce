<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\ProductCart;
use App\Entity\Product;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductCartRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class CartController extends AbstractController
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private ProductCartRepository $productCartRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository, ProductCartRepository $productCartRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->productCartRepository = $productCartRepository;
    }

    public function add_product_cart(Request $request): Response
    {
        $product = $this->productRepository->find($request->get('product_id'));
        $cart = $this->cartRepository->find($request->get('id'));

        if(!$cart) {
            // TODO Get user connected and create a cart
            return $this->buildNotFoundResponse("This cart doesn't exist.");
        }
        if(!$product) {
            return $this->buildNotFoundResponse("This product doesn't exist.");
        }

        $findProduct = $this->cartRepository->findProduct($cart, $product);
        
        if($findProduct != null) {
            $findProduct->setQuantity($findProduct->getQuantity() + 1);
            $product->setQuantity($product->getQuantity() - 1);
            $this->productRepository->save($product, true);
            $this->productCartRepository->save($findProduct, true);
        } else {
            // If product not existing create relation
            $productCart = new ProductCart();
            $productCart->setCart($cart);
            $productCart->setProduct($product);
            $productCart->setQuantity(1);
            $this->productCartRepository->save($productCart, true);
        }


        $cartProducts = $cart->getProductCarts();
        $total = $this->cartRepository->getTotalAmount($cart);
        $cart->setTotalAmount($total);
        $this->cartRepository->save($cart, true);

        $cartFormatted = array(
            "cart" => $cart,
            "products" => $cartProducts,
        );


        return $this->buildDataResponse($cartFormatted);
    }

    // public function remove_product_cart(Cart $cart, Product $product): Response
    // {
    //     if(!$cart) {
    //         // TODO Get user connected and create a cart
    //         return $this->buildNotFoundResponse("This cart doesn't exist.");
    //     }
    //     if(!$product) {
    //         return $this->buildNotFoundResponse("This product doesn't exist.");
    //     }

    //     $findProduct = $this->cartRepository->findProduct($cart, $product);

    //     if($findProduct) {
    //         $product->setQuantity($product->getQuantity() - 1);
    //         $this->productRepository->save($product, true);
    //     } else {
    //         $cart->addProduct($product);
    //     }

    //     $total = $this->cartRepository->getTotalAmount($cart);
    //     $cart->setTotalAmount($total);
    //     $this->cartRepository->save($cart, true);


    //     return $this->buildDataResponse($cart);
    // }

}