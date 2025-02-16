<?php

namespace App\Modules\Task\Services;

use App\Modules\Project\Events\TaskCreatedEvent;
use App\Modules\Task\Entity\Task;
use App\Modules\Task\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class TaskService
{
    public function __construct(
        private  readonly TaskRepository $taskRepository,
        private readonly EntityManagerInterface   $entityManager,
        private readonly EventDispatcherInterface $dispatcher
    ) {}

    public function getAllTask(): array
    {
        return $this->taskRepository->findAll();
    }

    public function createTask(Task $task): ?Task
    {
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


    public function updateTask(Task &$task): ?Task
    {
        $this->entityManager->flush();
        return $task;
    }

}
