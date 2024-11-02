<?php

namespace App\Modules\Task\Controller;

use App\Modules\Task\Services\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{

    public function  __construct(
        private readonly TaskService $taskService
    ){}

    #[Route('/allTask', name: 'task_all',methods: ["GET"])]
    public function getAllTask(): Response
    {
        $allTask = $this->taskService->getAllTask();
        return $this->json([
            'result' => true,
            'total' => count($allTask),
            'data' => $allTask,
            'error' => []
        ], );
    }

}