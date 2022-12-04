<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function get_categories(): Response
    {
        $data = $this->categoryRepository->findAll();
        return $this->json($data);
    }

    public function create_category(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['method' => 'POST']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);

        if (!$form->isValid()) {
            return $this->buildFormErrorResponse($form);
        }

        $this->categoryRepository->save($category, true);

        return $this->buildDataResponse($category);
    }

    
    public function update_category(Request $request, int $id) : Response
    {
        $category = $this->categoryRepository->find($id);

        if ($category === null) {
            return $this->buildNotFoundResponse();
        }

        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['method' => 'PUT']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);

        if (!$form->isValid()) {
            return $this->buildFormErrorResponse($form);
        }

        $this->categoryRepository->save($category, true);

        return $this->buildDataResponse($category);
    }

    public function update_caetgory(Request $request, int $id) : Response
    {
        $category = $this->categoryRepository->find($id);

        if ($category === null) {
            return $this->buildNotFoundResponse();
        }

        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['method' => 'PUT']
        );

        $parameters = json_decode($request->getContent(), true);
        $form->submit($parameters);

        if (!$form->isValid()) {
            return $this->buildFormErrorResponse($form);
        }

        $this->categoryRepository->save($category, true);

        return $this->buildDataResponse($category);
    }

    public function delete_category(int $id) : Response
    {
        $category = $this->categoryRepository->find($id);

        if ($category === null) {
            return $this->buildNotFoundResponse();
        }

        $this->categoryRepository->remove($category, true);

        return $this->buildEmptyResponse();
    }
}