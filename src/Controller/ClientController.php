<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\Type\ClientType;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function get_client_cart(Client $client): Response 
    {
        $cart = $client->getCart();
        $cartProducts = $cart->getProductCarts();

        
        $cartFormatted = array(
            "cart" => $cart,
            "products" => $cartProducts,
        );

        return $this->buildDataResponse($cartFormatted);
    }

    public function get_clients(): Response
    {
        $categories = $this->clientRepository->findAll();
        return $this->buildDataResponse($categories);
    }

    public function get_client(Client $client): Response
    {
        return $this->buildDataResponse($client);
    }

    public function create_client(Request $request): Response
    {
        $client = new Client();

        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'POST']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);
        if (!$form->isValid()) {
            return $this->buildFormErrorResponse($form);
        }

        $this->clientRepository->save($client, true);
        return $this->buildDataResponse($client);

    }

    public function update_client(Request $request, Client $client): Response
    {
        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'PUT']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);
        if (!$form->isValid()) {
            return $this->buildFormErrorResponse($form);
        }

        $this->clientRepository->save($client, true);
        return $this->buildDataResponse($client);

    }

    public function delete_client(Client $client): Response
    {
        $this->clientRepository->remove($client, true);
        return $this->buildEmptyResponse();
    }
}
