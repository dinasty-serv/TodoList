<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class Service
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var PasswordHasherFactoryInterface
     */
    protected $encoder;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PasswordHasherFactoryInterface $encoder
     */
    public function __construct(EntityManagerInterface $entityManager, PasswordHasherFactoryInterface $encoder)
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }
}