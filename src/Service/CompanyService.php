<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CompanyDto;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class CompanyService
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,  
        private readonly EntityManagerInterface $em, 
        private readonly ObjectMapperInterface $objectMapper, 
    ) {
    }

    public function createCompany(CompanyDto $dto): Company
    {
        $existingCompany = $this->companyRepository->findOneBy(['taxNumber' => $dto->taxNumber]);

        if ($existingCompany) {
            throw new \InvalidArgumentException('Company with this tax number already exists');
        }
        
        /** @var Company $company */
        $company = $this->objectMapper->map($dto, new Company());

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }

    public function listCompanies(int $limit = 50): array
    {
        $companies = $this->companyRepository->findBy([], ['id' => 'DESC'], $limit);

        return $companies;
    }

    public function getCompany(int $id): Company
    {
        $company = $this->companyRepository->find($id) ?? throw new NotFoundHttpException('Company not found');

        return $company;
    }

    public function updateCompany(int $id, CompanyDto $dto): Company
    {
        $existingCompany = $this->getCompany($id) ?? throw new NotFoundHttpException('Company not found');

        $this->objectMapper->map($dto, $existingCompany);
        $this->em->flush();

        return $existingCompany;
    }

    public function deleteCompany(int $id): void
    {
        $company = $this->getCompany($id);
        $this->em->remove($company);
        $this->em->flush();
    }
}
