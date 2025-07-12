<?php

namespace App\Modules\Task\Controller;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Task\Entity\Task;
use App\Modules\Task\Services\TaskService;
use App\Security\Entity\User;
use App\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/task', name: 'task_')]
class TaskController extends AbstractController
{

    public function  __construct(
        private readonly TaskService $taskService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly HelperAction $helperAction,
        private readonly TranslatorInterface $translator,
        private readonly UserService $userService
    ){}

    #[Route('/{project}/allTask', name: 'task_all', methods: ["GET"])]
    public function getAllTask(?Project $project): Response
    {
        if ($project === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('project_module.not_found'));
        }
        return $this->json(
            [
                'total' => count($this->taskService->getAllTask($project->getId())),
                'tasks' => $this->taskService->getAllTask($project->getId()),
            ], Response::HTTP_OK, [], ['groups' => ['task:read', 'user:read']]
        );
    }

    #[Route('/{project}/create', name: 'task_create', methods: ["POST"])]
    public function createTask(Request $request, ?Project $project): Response
    {
        if ($project === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('project_module.not_found'));
        }
        /* @var User $user */
        $user = $this->getUser();
        $result = false;
        /* @var Task $task */
        $task = $this->serializer->deserialize($request->getContent(), Task::class, 'json',
            ['groups' => ["task:read", "task:write", "task:create"]]
        );
        $task->setProject($project);

        $errors = $this->helperAction->handleErrors($this->validator->validate($task));
        if (count($errors) === 0) {
            $result = true;
            $task = $this->taskService->createTask($task, $user);
        }
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => $errors
        ], count($errors) === 0 ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST, [],
            ['groups' => ['task:read']]);

    }

    #[Route('/{project}/update/{task}', name: 'task_update', methods: ["PUT", "PATCH"])]
    public function updateTask(Request $request, ?Task $task, ?Project $project): Response
    {
        if ($project === null || $task === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('project_module.not_found'));
        }
        /** @var User $user */
        $user = $this->getUser();
        $this->serializer->deserialize($request->getContent(), Task::class, 'json',
            [
                'groups' => ["task:read", "task:write", "task:update"],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['project', 'assignedUsers'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $task
            ]
        );

       $data = json_decode($request->getContent(), true);
        foreach (array_column($data['assignedUsers'], 'id') as $key => $userId) {
            $user = $this->userService->getUserById($userId);
            if (!$user) {
                throw new \RuntimeException("User with ID $userId not found.");
            }
            $task->addAssignedUser($user);
        }
        $result = false;
        $errors = $this->helperAction->handleErrors($this->validator->validate($task));
        if (count($errors) === 0) {
            $result = true;
            $this->taskService->updateTask($task, $user, $data['checklist'] ?? []);
        }
        return $this->json([
            'result' => $result,
            'data' => $task,
            'error' => $errors
        ], count($errors) === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST, [], ['groups' => ['task:read', 'user:read']]);
    }

    #[Route('/{project}/delete/{task}', name: 'task_delete', methods: ["DELETE"])]
    public function deleteTask(Request $request, ?Task $task, ?Project $project): Response
    {
        if ($project === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('project_module.not_found'));
        }
        if (null === $task) {
            return $this->helperAction->jsonNotFoundOrError();
        }
        $this->taskService->deleteTask($task);
        return $this->json(['result' => true, 'error' => [] ], Response::HTTP_OK );
    }

}
