<?php

namespace App\Modules\Task\Controller;

use App\Helpers\HelperAction;
use App\Modules\Task\Entity\Task;
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
        private readonly HelperAction $helperAction
    ){}

    #[Route('/allTask', name: 'task_all',methods: ["GET"])]
    public function getAllTask(): Response
    {
        return $this->json(
            [
            'total' => count($this->taskService->getAllTask()),
                'tasks' => $this->taskService->getAllTask()
            ], Response::HTTP_OK, [], ['groups' => ['task:read']]
        );
    }

    #[Route('/create', name: 'task_create',methods: ["POST"])]
    public function createTask(Request $request): Response
    {
        $result = false;
        try {
            $task = $this->serializer->deserialize($request->getContent(), Task::class, 'json',
                ['groups' => ["task:read", "task:write", "task:create"]]
            );
            $errors = $this->helperAction->handleErrors($this->validator->validate($task));
            if (count($errors) === 0) {
                $result = true;
                $task = $this->taskService->createTask($task);
            }
            return $this->json([
                'result' => $result,
                'data' => $task ?? null,
                'error' => $errors
            ], count($errors) === 0 ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST, [],
                ['groups' => ['task:read']]);

        } catch (\Exception $e) {
            return $this->helperAction->jsonNotFoundOrError($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

    }

    #[Route('/update/{task}', name: 'task_update', methods: ["PUT", "PATCH"])]
    public function updateTask(Request $request, ?Task $task): Response
    {
        $this->serializer->deserialize($request->getContent(), Task::class, 'json',
            [
                'groups' => ["task:read", "task:write", "task:update"],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['project'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $task
            ]
        );
        $result = false;
        $errors = $this->helperAction->handleErrors($this->validator->validate($task));
        if (count($errors) === 0) {
            $result = true;
            $this->taskService->updateTask($task);
        }
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => $errors
        ], count($errors) === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST, [], ['groups' => ['task:read']]);



    }

    #[Route('/delete/{task}', name: 'task_delete', methods: ["DELETE"])]
    public function deleteTask(Request $request, ?Task $task): Response
    {
        if (null === $task) {
            return $this->helperAction->jsonNotFoundOrError();
        }
        $this->taskService->deleteTask($task);
        return $this->json(['result' => true, 'error' => [] ], Response::HTTP_OK );
    }

}
