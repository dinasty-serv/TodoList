<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;



class TaskVoter extends Voter
{


    const editTask = 'editTask';
    const deleteTask = 'deleteTask';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param  string $attribute
     * @param  mixed  $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::editTask, self::deleteTask])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    /**
     * @param  string         $attribute
     * @param  mixed          $subject
     * @param  TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        $task = $subject;

        switch ($attribute) {
            case self::editTask:
                return $this->caneditTask($task, $user);
            case self::deleteTask:
                return $this->candeleteTask($task, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }


    /**
     * @param  Task $task
     * @param  User $user
     * @return bool
     */
    private function caneditTask(Task $task, User $user): bool
    {
        return $user === $task->getUser();
    }

    /**
     * @param  Task $task
     * @param  User $user
     * @return bool
     */
    private function candeleteTask(Task $task, User $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $user === $task->getUser()) {
            return true;
        }
    }
}