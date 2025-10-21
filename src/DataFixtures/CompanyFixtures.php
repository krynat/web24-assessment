<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 5 fake companies
        for ($i = 0; $i < 5; $i++) {
            $company = new Company();
            $company
                ->setName($faker->company())
                ->setTaxNumber($faker->unique()->numerify('############'))
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setPostcode($faker->postcode())
            ;

            $manager->persist($company);

            // Add reference for EmployeeFixtures
            $this->addReference('company_' . $i, $company);
        }

        $manager->flush();
    }
}
