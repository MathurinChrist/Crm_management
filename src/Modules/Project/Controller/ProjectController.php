<?php

namespace App\Modules\Project\Controller;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Project\Services\ProjectService;
use App\Security\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/project', name: 'project')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly SerializerInterface $serializer,
        private readonly HelperAction $helperAction,
        private readonly ValidatorInterface $validator

    ){}

    #[Route('/allProject', name: '_all',methods: ["GET"])]
    public function getAllProjects(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $allProjects = $this->projectService->getAllProjects($user);
        return $this->json([
            'total' => count($allProjects),
            'data' => $allProjects,
            'error' => []
        ], Response::HTTP_OK, [], ['groups' => ["project:read", 'user:read']]);
    }

    #[Route('/create', name: '_create',methods: ["POST"])]
    public function createProject(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $result = false;
        $project = $this->serializer->deserialize($request->getContent(), Project::class, 'json', [
            'groups' => ["project:create"]
        ]);
        $errors = $this->helperAction->handleErrors($this->validator->validate($project));
        if (count($errors) === 0) {
            $project = $this->projectService->createProject($project, $user);
            $result = true;
        }
        $codeStatus = count($errors) === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        return $this->json(
            [
                'result' => $result,
                'data' => $project,
                'errors' => $errors
            ], $codeStatus, [], ['groups' => ['project:read', 'user:read']]);
    }

    #[Route('/{project}', name: 'update', methods: ["PUT", "PATCH"])]
    public function updateProject(Request $request, ?Project $project): Response
    {
        if ($project === null) {
            return $this->helperAction->jsonNotFoundOrError('project_module.not_found');
        }

        $this->serializer->deserialize($request->getContent(), Project::class, 'json', [
            'groups' => ["project:create"],
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['tasksNumber'],
            AbstractNormalizer::OBJECT_TO_POPULATE => $project
        ]);


        $errors = $this->validator->validate($project);
        if(count($errors) ===0){
            $project = $this->projectService->updateProject($project);
        }
        return $this->json(
            [
                'result' => true,
                'data' => $project,
                'error' => []
            ], Response::HTTP_OK, [], ['groups' => ['project:read', 'user:read']]);
    }

    #[Route('/delete/{id}', name: '_delete', methods: ["DELETE"])]
    public function deleteProject(int $id): Response
    {
        $project = $this->projectService->getProjectById($id);
        if ($project === null) {
            return $this->helperAction->JsonNotFoundOrError('project_module.not_found');
        }

        $this->projectService->deleteProject($project);
        return $this->json(
            [
                'result' => true,
                'error' => []
            ], Response::HTTP_OK);
    }
}
