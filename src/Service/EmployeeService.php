<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\EmployeeDto;
use App\Entity\Employee;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly CompanyRepository $companyRepository,
        private readonly EntityManagerInterface $em,
        private readonly ObjectMapperInterface $objectMapper,
    ) {
    }

    public function createEmployee(EmployeeDto $employeeDto): Employee
    {
        $company = $this->companyRepository->find($employeeDto->company);

        if (!$company) {
            throw new \InvalidArgumentException('Company with ID '.$employeeDto->company.' not found.');
        }

        $employee = $this->employeeRepository->findOneBy((array) $employeeDto);

        if ($employee) {
            throw new \InvalidArgumentException('Employee with this tax number already exists');
        }

        $newEmployee = new Employee();
        $newEmployee->setCompany($company);
        /** @var Employee $employee */
        $employee = $this->objectMapper->map($employeeDto, $newEmployee);
        $this->em->persist($employee);
        $this->em->flush();

        return $employee;
    }

    public function listEmployees(?int $companyId = null, int $limit = 50): array
    {
        $employees = $this->employeeRepository->findBy(['company' => $companyId], ['id' => 'DESC'], $limit);

        return $employees;
    }

    public function getEmployee(int $id): Employee
    {
        $employee = $this->employeeRepository->find($id) ?? throw new NotFoundHttpException('Employee not found');

        return $employee;
    }

    public function updateEmployee(int $id, EmployeeDto $employeeDto): Employee
    {
        $existingEmployee = $this->getEmployee($id) ?? throw new NotFoundHttpException('Employee not found');

        $this->objectMapper->map($employeeDto, $existingEmployee);
        $this->em->flush();

        return $existingEmployee;
    }

    public function deleteEmployee(int $id): void
    {
        $employee = $this->getEmployee($id);
        $this->em->remove($employee);
        $this->em->flush();
    }
}
