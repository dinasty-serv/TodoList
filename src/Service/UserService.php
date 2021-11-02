<?php

namespace App\Service;

use App\Entity\User;

class UserService extends Service
{
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
    /**
     * @Description delete User
     * @param User $user
     * @author Nicolas de Fontaine
     */
    public function delete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
