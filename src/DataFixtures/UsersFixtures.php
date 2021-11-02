<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsersFixtures extends Fixture{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {

            $user = new User();
            $user->setEmail("test@email".$i.".com");
            $user->setUsername("user".$i);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->encodePassword($user, '0000'));

            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail("admin@email.com");
        $user->setUsername("admin");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user, '0000'));

        $manager->persist($user);




        $manager->flush();
    }
}
