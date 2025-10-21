<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use Faker\Factory as Faker;

trait DataGeneratorTrait
{
    protected static function generateFakeCompany(): array
    {
        $faker = Faker::create();

        return [
            'name' => $faker->company(),
            'taxNumber' => $faker->unique()->numerify('############'),
            'address' => $faker->streetAddress(),
            'city' => $faker->city(),
            'postcode' => $faker->postcode(),
        ];
    }

    protected function generateFakeEmployee(int $companyId): array
    {
        $faker = Faker::create();

        return [
            'firstName' => $faker->firstName(),
            'lastName' => $faker->lastName(),
            'email' => $faker->unique()->safeEmail(),
            'phone' => $faker->phoneNumber(),
            'company' => $companyId,
        ];
    }
}