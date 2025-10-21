<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CompanyDto;
use App\Service\CompanyService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/companies', name: 'api_companies_')]
class CompanyApiController extends AbstractController
{
    public function __construct(
        private readonly CompanyService $companyService,
    ) {
    }

    #[OA\Get(
        path: '/api/companies',
        summary: 'Get list of companies',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns companies',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: CompanyDto::class))
                )
            ),
        ]
    )]
    #[Route('', name: 'list', methods: ['GET'], format: 'json')]
    public function list(): JsonResponse
    {
        $companies = $this->companyService->listCompanies();

        return $this->json($companies, Response::HTTP_OK);
    }

    #[OA\Get(
        path: '/api/companies/{id}',
        summary: 'Get single company',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Company details',
                content: new OA\JsonContent(ref: new Model(type: CompanyDto::class))
            ),
            new OA\Response(response: 404, description: 'Company not found'),
        ]
    )]
    #[Route('/{id}', name: 'get', methods: ['GET'], format: 'json')]
    public function get(int $id): JsonResponse
    {
        $company = $this->companyService->getCompany($id);

        return $this->json($company, Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/companies',
        summary: 'Create a new company',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CompanyDto::class))
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Company created',
                content: new OA\JsonContent(ref: new Model(type: CompanyDto::class))
            ),
        ]
    )]
    #[Route('', name: 'create', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload] CompanyDto $dto,
    ): JsonResponse {
        $company = $this->companyService->createCompany($dto);

        return $this->json($company, Response::HTTP_CREATED);
    }

    #[OA\Put(
        path: '/api/companies/{id}',
        summary: 'Update company by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: CompanyDto::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Company updated',
                content: new OA\JsonContent(ref: new Model(type: CompanyPostDto::class))
            ),
            new OA\Response(response: 404, description: 'Company not found'),
        ]
    )]
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'], format: 'json')]
    public function update(
        int $id,
        #[MapRequestPayload] CompanyDto $dto,
    ): JsonResponse {
        $company = $this->companyService->updateCompany($id, $dto);

        return $this->json($company, Response::HTTP_OK);
    }

    #[OA\Delete(
        path: '/api/companies/{id}',
        summary: 'Delete company by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Company deleted'),
            new OA\Response(response: 404, description: 'Company not found'),
        ]
    )]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $this->companyService->deleteCompany($id);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
