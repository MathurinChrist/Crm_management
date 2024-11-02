<?php

namespace App\Modules\Task\Controller;

use App\Modules\Task\Entity\Task;
use App\Modules\Task\Services\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/task', name: 'task_')]
class TaskController extends AbstractController
{

    public function  __construct(
        private readonly TaskService $taskService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ){}

    #[Route('/allTask', name: 'task_all',methods: ["GET"])]
    public function getAllTask(): Response
    {
        //todo! we've to come back for roles
        $allTask = $this->taskService->getAllTask();
        $this->serializer->serialize($allTask, 'json');

        return $this->json([
            'result' => true,
            'total' => count($allTask),
            'data' => $allTask,
            'error' => []
        ]);
    }

    #[Route('/create', name: 'task_create',methods: ["POST"])]
    public function createTask(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $result = true;
        $task = $this->serializer->deserialize($request->getContent(),Task::class, 'json');
        $errors = $this->validator->validate($task);
        $this->taskService->createTask($task);
        //todo: add validator and conditio, for errors if exist
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => []
        ]);

    }
}
