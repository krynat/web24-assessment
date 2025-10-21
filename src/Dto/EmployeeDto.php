<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Employee;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Employee::class)]
class EmployeeDto
{
    #[Map(target: Employee::class, source: null)]
    public ?int $id = null;

    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\NotBlank]
    public string $lastName;

    #[Assert\NotBlank, Assert\Email]
    public string $email;

    public ?string $phone = null;

    #[Assert\NotBlank]
    #[Map(target: 'getCompany')]
    public int $company;
}
