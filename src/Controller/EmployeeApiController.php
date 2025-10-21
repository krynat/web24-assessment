<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\EmployeeDto;
use App\Service\EmployeeService;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class EmployeeApiController extends AbstractController
{
    public function __construct(
        private readonly EmployeeService $employeeService, 
    ) {
    }

    #[OA\Get(
        path: '/api/employee',
        summary: 'Get list of employees',
        parameters: [
            new OA\Parameter(name: 'company', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns employees',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: EmployeeDto::class)))
            )
        ]
    )]
    #[Route('/companies/{companyId}/employees', name: 'employee_list', methods: ['GET'], format: 'json')]
    public function listByCompany(Request $request, ?int $companyId = null): JsonResponse
    {
        $companyId = $request->query->getInt('company', 0) ?: null;
        $employees = $this->employeeService->listEmployees($companyId);

        return $this->json($employees, Response::HTTP_OK);
    }

    #[OA\Get(
        path: '/api/employee/{id}',
        summary: 'Get employee by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the employee',
                content: new OA\JsonContent(ref: new Model(type: EmployeeDto::class))
            ),
            new OA\Response(response: 404, description: 'Employee not found')
        ]
    )]
    #[Route('/employees/{id}', name: 'employee_get', methods: ['GET'], format: 'json')]
    public function get(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployee($id);

        return $this->json($employee, Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/companies/{companyId}/employees',
        summary: 'Create a new employee',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: EmployeeDto::class))
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Employee created',
                content: new OA\JsonContent(ref: new Model(type: EmployeeDto::class))
            )
        ]
    )]
    #[Route('/companies/{companyId}/employees', name: 'employee_create', methods: ['POST'], format: 'json')]
    public function create(#[MapRequestPayload] EmployeeDto $dto): JsonResponse {
        $employee = $this->employeeService->createEmployee($dto);

        return $this->json($employee, Response::HTTP_CREATED);
    }

    #[OA\Put(
        path: '/api/employees/{id}',
        summary: 'Update employee by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: new Model(type: EmployeeDto::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee updated',
                content: new OA\JsonContent(ref: new Model(type: EmployeeDto::class))
            ),
            new OA\Response(response: 404, description: 'Employee not found')
        ]
    )]
    #[Route('/employees/{id}', name: 'employee_update', methods: ['PUT', 'PATCH'], format: 'json')]
    public function update(
        int $id,
        #[MapRequestPayload] EmployeeDto $dto,
    ): JsonResponse {
        $employee = $this->employeeService->updateEmployee($id, $dto);

        return $this->json($employee, Response::HTTP_OK);
    }

    #[OA\Delete(
        path: '/api/employees/{id}',
        summary: 'Delete employee by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 204, description: 'Employee deleted'),
            new OA\Response(response: 404, description: 'Employee not found')
        ]
    )]
    #[Route('/employees/{id}', name: 'employee_delete', methods: ['DELETE'], format: 'json')]
    public function delete(int $id): JsonResponse
    {
        $this->employeeService->deleteEmployee($id);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
