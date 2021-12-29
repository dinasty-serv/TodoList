<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\Service\Authentificator;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskControllerTest extends WebTestCase
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
    public function testListTaskNotLogin()
    {

        $this->client->getClientNotLogin()->request('GET', '/task');
        $this->assertEquals(302, $this->client->getClientNotLogin()->getResponse()->getStatusCode());
    }

    /**
     * Test acces index page login user
     */
    public function testListTaskLogin()
    {
        $this->client->getClientLoginUser()->request('GET', '/');
        $this->assertEquals(200, $this->client->getClientLoginUser()->getResponse()->getStatusCode());
    }

    /**
     * Test create new task
     * @return void
     */
    public function testCreateNewTask()
    {

        $crawler = $this->client->getClientLoginUser()->request('GET', '/task/new');
        $createTaskForm = $crawler->selectButton("Enregistrer")->form();
        $titleTest = 'Test title task with functionnal testFormAddTask';
        $contentTest = 'Test content task with functionnal testFormAddTask';

        $createTaskForm['task[title]'] = $titleTest;
        $createTaskForm['task[content]'] = $contentTest;
        $this->client->getClientLoginUser()->submit($createTaskForm);
        $crawler = $this->client->getClientLoginUser()->followRedirect();
        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('La tâche a été bien été ajoutée.', $successMessage);
    }

    /**
     * Test edit task
     * @return void
     */
    public function testEditTask()
    {
        $client = $this->client->getClientLoginUser();
        $user = $client->getContainer()->get(TokenStorageInterface::class)->getToken()->getUser();
        $taskId = $this->entityManager->getRepository(Task::class)->findOneBy(['user' => $user->getId()]);

        $crawler = $client->request(
            'GET',
            '/task/' . $taskId->getId() . '/edit'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $createTaskForm = $crawler->selectButton("Enregistrer")->form();
        $titleTest = 'Test edit title task with functionnal testFormAddTask';
        $contentTest = 'Test edit content task with functionnal testFormAddTask';

        $createTaskForm['task[title]'] = $titleTest;
        $createTaskForm['task[content]'] = $contentTest;
        $client->submit($createTaskForm);
        $crawler = $client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('Superbe ! La tâche a été bien été modifé.', $successMessage);
    }

    /**
     * Test delete task
     * @return void
     */
    public function testDeleteTask()
    {
        $client = $this->client->getClientLoginUser();
        $crawler = $client->request(
            'GET',
            '/task/'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $createTaskForm = $crawler->selectButton("Supprimer")->form();
        $client->submit($createTaskForm);
        $crawler = $client->followRedirect();
        $successMessage = $crawler->filter('div.alert.alert-success')->text();
        $this->assertStringContainsString('La tâche a été bien été supprimé.', $successMessage);
    }

    /**
     * Test Toggle Task
     * @return void
     */
    public function testToggleTask()
    {

        $client = $this->client->getClientLoginUser();

        $crawler = $client->request(
            'GET',
            '/task/'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton("Marquer comme faite")->form();
        $client->submit($form);
        $client->followRedirect();
    }
}
