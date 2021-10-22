<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Service\Authentificator;

class DefaultControllerTest extends WebTestCase
{
    private $client;
    public function setUp(): void
    {
        $client = new Authentificator(static::createClient(), static::getContainer()->get(EntityManagerInterface::class));
        $this->client = $client;
    }
    /**
     * Test acces index page not login user
     */
    public function testIndexNotLogin()
    {

        $this->client->getClientNotLogin()->request('GET', '/');
        $this->assertEquals(302, $this->client->getClientNotLogin()->getResponse()->getStatusCode());
    }

    /**
     * Test acces index page login user
     */
    public function testIndexLogin()
    {
        $this->client->getClientLoginUser()->request('GET', '/');
        $this->assertEquals(200, $this->client->getClientLoginUser()->getResponse()->getStatusCode());
    }

    /**
     * Test acces login page not login user
     */
    public function testLoginPage()
    {
        $this->client->getClientNotLogin()->request('GET', '/login');
        $this->assertEquals(200, $this->client->getClientNotLogin()->getResponse()->getStatusCode());
    }
}