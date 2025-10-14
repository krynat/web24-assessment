<?php
declare(strict_types=1);

namespace App\Mapper;

use App\Dto\CompanyDto;
use App\Entity\Company;
use App\Entity\Employee;

class CompanyMapper
{
    public function mapDtoToEntity(CompanyDto $dto): Company
    {
        $company = new Company();
        $company->setName($dto->name);
        $company->setTaxNumber($dto->taxNumber);
        $company->setAddress($dto->address);
        $company->setCity($dto->city);
        $company->setPostcode($dto->postCode);

        foreach ($dto->employees as $employeeDto) {
            $employee = new Employee();
            $employee->setFirstName($employeeDto->firstName);
            $employee->setLastName($employeeDto->lastName);
            $employee->setEmail($employeeDto->email);
            $employee->setPhone($employeeDto->phone);
            $employee->setCompany($company);
            $company->addEmployee($employee);
        }

        return $company;
    }
}