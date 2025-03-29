<?php

namespace App\Modules\Task\Services;

use App\Helpers\HelperAction;
use App\Modules\Project\Events\TaskCreatedEvent;
use App\Modules\Task\Entity\Task;
use App\Modules\Task\Repository\TaskRepository;
use App\Security\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class TaskService
{
    public function __construct(
        private  readonly TaskRepository $taskRepository,
        private readonly EntityManagerInterface   $entityManager,
        private readonly EventDispatcherInterface $dispatcher
    ) {}

    public function getAllTask(int $projectId): array
    {
        return $this->taskRepository->findBy(['project' => $projectId]);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(Task $task, User $user): ?Task
    {
        HelperAction::SetCreateOrUpdateBy($task, $user);
        $this->dispatcher->dispatch(new TaskCreatedEvent($task));
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }

    public function deleteTask(Task &$task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }


    public function updateTask(Task &$task, User $user): ?Task
    {
        HelperAction::SetCreateOrUpdateBy($task, $user);
        $this->entityManager->flush();
        return $task;
    }

}
