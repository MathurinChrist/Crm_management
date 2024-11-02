<?php

namespace App\Modules\Task\Services;

use App\Modules\Task\Entity\Task;
use App\Modules\Task\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private  readonly TaskRepository $taskRepository,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getAllTask(): array
    {
        return $this->taskRepository->findAll();
    }

    public function createTask(Task &$task): ?Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $task;
    }

}
