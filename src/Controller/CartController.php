<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\ProductCart;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderEntry;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductCartRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class CartController extends AbstractController
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;
    private ProductCartRepository $productCartRepository;
    private OrderRepository $orderRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository, ProductCartRepository $productCartRepository, OrderRepository $orderRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->productCartRepository = $productCartRepository;
        $this->orderRepository = $orderRepository;
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
            $product->setQuantity($product->getQuantity() - 1);
            $this->productRepository->save($product, true);
            $this->productCartRepository->save($productCart, true);
        }

        //TODO Add error bug
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

    public function remove_product_cart(Request $request): Response
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
            $findProduct->setQuantity($findProduct->getQuantity() - 1);
            $product->setQuantity($product->getQuantity() + 1);
            $this->productRepository->save($product, true);
            if($findProduct->getQuantity() == 0) {
                $this->productCartRepository->remove($findProduct, true);
            }
            else {
                $this->productCartRepository->save($findProduct, true);
            }
        } else {
            return $this->buildNotFoundResponse("This item does not exist in the cart.");
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

    public function remove_cart(Cart $cart): void
    {
        $cartProducts = $cart->getProductCarts();

        if(count($cartProducts) > 0) {
            for ($i=0; $i < count($cartProducts); $i++) { 
                $entity = $cartProducts[$i];
                $this->productCartRepository->remove($entity, true);
            }
        }

        $this->cartRepository->remove($cart, true);

    }

    public function delete_cart(Cart $cart): Response
    {
        if ($cart === null) {
            return $this->buildNotFoundResponse();
        }

        self::remove_cart($cart);
        
        return $this->buildEmptyResponse($cart);
    }

    // TODO When deleting first time
    public function validate_cart(Cart $cart): Response
    {
        if ($cart === null) {
            return $this->buildNotFoundResponse();
        }

        $cartProducts = $cart->getProductCarts();
        $client = $cart->getClient();
        $total = $cart->getTotalAmount();
        $address = $client->getAddresses()[0];

        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setTotal($total);
        $order->setCode("0000");
        $order->setPaymentMethod(1);
        $order->setAddressUsed("{$address->getStreet()}, {$address->getCity()} {$address->getPostalCode()}");
        $order->setClient($client);

        for ($i=0; $i < count($cartProducts) ; $i++) { 
            $product = $cartProducts[$i]->getProduct();
            $orderEntry = new OrderEntry();
            $orderEntry->setName($product->getName());
            $orderEntry->setQuantity($cartProducts[$i]->getQuantity());
            $orderEntry->setPrice($product->getPrice());
            $orderEntry->setShortDescription($product->getShortDescription());
            $orderEntry->setDescription($product->getDescription());
            $orderEntry->setCreatedAt(new \DateTimeImmutable());
            $order->addOrderEntry($orderEntry);
        }

        $this->orderRepository->save($order, true);

        self::remove_cart($cart);

        return $this->buildEmptyResponse();
    }

}