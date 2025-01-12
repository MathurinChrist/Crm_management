<?php

namespace App\Modules\Task\Controller;

use App\Helpers\HelperAction;
use App\Modules\Task\Entity\Task;
use App\Modules\Task\Repository\TaskRepository;
use App\Modules\Task\Services\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/task', name: 'task_')]
class TaskController extends AbstractController
{

    public function  __construct(
        private readonly TaskService $taskService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly  TaskRepository $taskRepository,
        private readonly HelperAction $helperAction
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
        $result = true;
        $task = $this->serializer->deserialize($request->getContent(),Task::class, 'json');
        $errors = $this->validator->validate($task);
        $errors =  $this->helperAction->handleErrors($errors);
        if( count($errors) === 0 ){
            $this->taskService->createTask($task);
        } else {
            $result = false;
            $task =  null;
        }
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => $errors
        ], count($errors) ===0? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);

    }

    #[Route('/update/{task}', name: 'task_update', methods: ["PUT", "PATCH"])]
    public function updateTask(Request $request, ?Task $task): Response
    {
        if (null === $task) {
            return $this->helperAction->jsonNotFound();
        }
        $result = true;
        $this->serializer->deserialize($request->getContent(),Task::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $task]);
        $errors = $this->validator->validate($task);
        $errors =  $this->helperAction->handleErrors($errors);
        if( count($errors) === 0 ){
            $this->taskService->updateTask($task);
        } else {
            $result = false;
            $task =  null;
        }
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => $errors
        ], count($errors) ===0? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST);
    }

    #[Route('/delete/{task}', name: 'task_delete', methods: ["DELETE"])]
    public function deleteTask(Request $request, ?Task $task): Response
    {
        if (null === $task) {
            return $this->helperAction->jsonNotFound();
        }
        $this->taskService->deleteTask($task);
        return $this->json(['result' => true, 'error' => [] ], Response::HTTP_OK );
    }

}
