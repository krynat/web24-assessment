<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Helper\DataGeneratorTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EmployeeApiControllerTest extends WebTestCase
{
    use DataGeneratorTrait;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testCreateEmployee(): void
    {
        $companyPayload = $this->generateFakeCompany();
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($companyPayload));
        $companyResponse = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $employeePayload = $this->generateFakeEmployee($companyResponse['id']);
        $this->client->request('POST', '/api/companies/'.$employeePayload['company'].'/employees', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($employeePayload));
        $employeeResponse = json_decode($this->client->getResponse()->getContent(), true);
        $employeePayload['id'] = $employeeResponse['id'];

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $employeeResponse);
        $this->assertEquals($employeePayload, $employeeResponse);
    }
}
