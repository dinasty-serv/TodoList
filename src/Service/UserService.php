<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordHasherFactoryInterface
     */
    private $encoder;

    public function __construct(EntityManagerInterface $entityManager, PasswordHasherFactoryInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @Description create new User
     * @param User $user
     * @author Nicolas de Fontaine
     */
    public function createOrUpdate(User $user)
    {
        $password = $this->encoder->getPasswordHasher($user)->hash($user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
