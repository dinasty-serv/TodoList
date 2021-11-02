<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Service\Authentificator;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserControllerTest extends WebTestCase
{
    /**
     * @var Authentificator
     */
    private $client;
    /**
     * @var EntityManager|object|null
     */
    private $entityManager;


    public function setUp(): void
    {
        $client = new Authentificator(
            static::createClient(),
            static::getContainer()->get(EntityManagerInterface::class)
        );
        $this->client = $client;
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }
    /**
     * Test acces index page not login user
     */
    public function testListUserNotLogin()
    {

        $this->client->getClientNotLogin()->request('GET', '/task');
        $this->assertEquals(302, $this->client->getClientNotLogin()->getResponse()->getStatusCode());
    }

    /**
     * Test acces index page login user
     */
    public function testListUserLogin()
    {
        $this->client->getClientLoginAdmin()->request('GET', '/');
        $this->assertEquals(200, $this->client->getClientLoginUser()->getResponse()->getStatusCode());
    }

    public function testCreateNewUser()
    {
        $client =  $this->client->getClientLoginAdmin();
        $crawler = $client->request('GET', '/user/new');
        $createUserForm = $crawler->selectButton("Enregistrer")->form();

        $num = mt_rand(20, 50);
        $createUserForm['user[email]'] = "testajout" . $num . "@email.com";
        $createUserForm['user[username]'] = "testAjout";
        $createUserForm['user[password][first]'] = "0000";
        $createUserForm['user[password][second]'] = "0000";
        $createUserForm['user[roles]'] = "ROLE_USER";

        $client->submit($createUserForm);
        $crawler = $client->followRedirect();
        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('Superbe ! L\'utilisateur à bien été ajouté !', $successMessage);
    }

    public function testEditUser()
    {
        $client = $this->client->getClientLoginAdmin();
        $user = $this->entityManager->getRepository(User::class)->findOneBy([], ['id' => 'DESC'], 1);
        $crawler = $client->request(
            'GET',
            '/user/' . $user->getId() . '/edit'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $updateUserForm = $crawler->selectButton("Enregistrer")->form();
        $num = mt_rand(20, 50);

        $updateUserForm['user[email]'] = "testEdit" . $num . "@email.com";
        $updateUserForm['user[username]'] = "testEdit";
        $updateUserForm['user[password][first]'] = "0000";
        $updateUserForm['user[password][second]'] = "0000";
        $updateUserForm['user[roles]'] = "ROLE_USER";
        $client->submit($updateUserForm);
        $crawler = $client->followRedirect();
        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('Superbe ! L\'utilisateur à bien été modifié !', $successMessage);
    }

    public function testShowUser()
    {

        $client = $this->client->getClientLoginAdmin();

        $crawler = $client->request(
            'GET',
            '/user/'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $link = $crawler->selectLink("show")->link();
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUser()
    {
        $client = $this->client->getClientLoginAdmin();
        $crawler = $client->request(
            'GET',
            '/user/'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $link = $crawler->selectLink("show")->link();
        $crawler = $client->click($link);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton("Supprimer")->form();
        $client->submit($form);
        $crawler = $client->followRedirect();
        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('L\'utilisateur à bien été supprimé !', $successMessage);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
