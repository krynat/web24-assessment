<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Company;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyDto
{
    #[Map(target: Company::class, source: null)]
    public ?int $id = null;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $taxNumber;

    #[Assert\NotBlank]
    public string $address;

    #[Assert\NotBlank]
    public string $city;

    #[Assert\NotBlank]
    public string $postcode;
}
