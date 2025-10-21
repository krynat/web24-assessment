<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Employee;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EmployeeNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer, 
    ) {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $data = [
            'id' => $data->getId(),
            'firstName' => $data->getFirstName(),
            'lastName' => $data->getLastName(),
            'email' => $data->getEmail(),
            'phone' => $data->getPhone(), 
            'company' => $data->getCompany()?->getId(),
        ];

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Employee;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Employee::class => true,
        ];
    }
}
