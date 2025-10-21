<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Company;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CompanyNormalizer implements NormalizerInterface
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
            'name' => $data->getName(),
            'taxNumber' => $data->getTaxNumber(),
            'address' => $data->getAddress(),
            'city' => $data->getCity(),
            'postcode' => $data->getPostcode(),
        ];

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Company;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Company::class => true,
        ];
    }
}
