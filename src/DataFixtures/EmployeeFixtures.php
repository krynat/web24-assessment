<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 3-7 employees for each company
        for ($i = 0; $i < 5; ++$i) {
            $company = $this->getReference('company_'.$i, Company::class);

            for ($j = 0; $j < rand(3, 7); ++$j) {
                $employee = new Employee();
                $employee
                    ->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->unique()->email())
                    ->setPhone($faker->phoneNumber())
                    ->setCompany($company)
                ;

                $manager->persist($employee);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
