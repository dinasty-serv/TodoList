<?php

namespace App\Tests\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class Authentificator
{

    /**
     * @var KernelBrowser
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(KernelBrowser $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getClientNotLogin(){
        return $this->client;
    }

    public function getClientLoginAdmin(){

        $user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => "admin@email.com"]);

        // simulate $testUser being logged in
        $this->client->loginUser($user);
        return $this->client;
    }

    public function getClientLoginUser()
    {

        $user = $this->entityManager->getRepository(User::class)->findOneBy(["email" => "test@email4.com"]);

        // simulate $testUser being logged in
        $this->client->loginUser($user);
        return $this->client;
    }

}