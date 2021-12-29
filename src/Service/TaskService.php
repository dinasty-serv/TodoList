<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Security;

class TaskService extends Service
{
    /**
     * @var Security $security
     */
    private $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PasswordHasherFactoryInterface $encoder
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        PasswordHasherFactoryInterface $encoder,
        Security $security
    ) {
        parent::__construct($entityManager, $encoder);
        $this->security = $security;
    }

    /**
     * @Description create or update task
     * @param Task $task
     * @author Nicolas de Fontaine
     */
    public function createOrUpdate(Task $task)
    {
        $task->setUser($this->security->getToken()->getUser());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
    /**
     * @Description delete User
     * @param Task $task
     * @author Nicolas de Fontaine
     */
    public function delete(Task $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @Description set status task
     * @param Task $task
     * @author Nicolas de Fontaine
     */
    public function toggle(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
