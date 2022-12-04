<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function get_client_orders(Request $request): Response
    {
        $id = $request->query->get('client_id');
        $orders = $this->orderRepository->findAllByClient($id);
        return $this->buildDataResponse($orders);
    }

}