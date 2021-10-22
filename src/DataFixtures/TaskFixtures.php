<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Task;


class TaskFixtures extends Fixture implements DependentFixtureInterface{


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user){

            for ($i = 1; $i <= 10; $i++) {

                $task = new Task();
                $task->setTitle("task ".$i);
                $task->setContent("content task ".$i);
                if (rand(0, 1)) {
                    $task->setUser($user);
                }else{
                    $task->setUser(null);
                }
                    $task->toggle(rand(0, 1));


                $manager->persist($task);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }
}
