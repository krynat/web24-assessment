<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Helper\DataGeneratorTrait;
use App\Tests\Helper\PostDataTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CompanyApiControllerTest extends WebTestCase
{
    use DataGeneratorTrait;

    private KernelBrowser $client;
    private static array $companyPayload;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateCompany(): void
    {
        $payload = $this->generateFakeCompany();
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $payload += ['id' => $response['id']];

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals($payload, $response);
    }

    public function testGetCompany(): void
    {
        $payload = $this->generateFakeCompany();
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request('GET', '/api/companies/' . $response['id']);

        $getResponse = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals($response, $getResponse);
    }

    public function testUpdateCompany(): void
    {
        $payload = $this->generateFakeCompany();
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $updatePayload = $this->generateFakeCompany();
        $this->client->request('PUT', '/api/companies/' . $response['id'], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($updatePayload));
        $updatedResponse = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($response['id'], $updatedResponse['id']);
    }

    public function testDeleteCompany(): void
    {
        $payload = $this->generateFakeCompany();
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($payload));

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->request('DELETE', '/api/companies/' . $response['id']);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
