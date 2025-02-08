<?php

namespace App\Modules\Project\Controller;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Project\Services\ProjectService;
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
        $allProjects = $this->projectService->getAllProjects();
        return $this->json([
            'total' => count($allProjects),
            'data' => $allProjects,
            'error' => []
        ], Response::HTTP_OK, [], ['groups' => ["project:read"]]);
    }

    #[Route('/create', name: '_create',methods: ["POST"])]
    public function createProject(Request $request): Response
    {
        $project = $this->serializer->deserialize($request->getContent(), Project::class, 'json');
        //todo: add validation before sending to the database
        $result = $this->projectService->createProject($project);
        return $this->json(
            [
            'result' => true,
            'data' => $result,
            'error' => []
            ], Response::HTTP_OK);
    }

    #[Route('/{id}', name: '_update', methods: ["PUT", "PATCH"])]
    public function updateProject(Request $request, int $id): Response
    {
        $project = $this->projectService->getProjectById($id);
        if($project === null){ return $this->helperAction->jsonNotFound(); }

        $this->serializer->deserialize($request->getContent(), Project::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $project]);
        $errors = $this->validator->validate($project);
        if(count($errors) ===0){
            $this->projectService->updateProject($project);
        }
        return $this->json(
            [
                'result' => true,
                'data' => $project,
                'error' => []
            ], Response::HTTP_OK);
    }

    #[Route('/{id}', name: '_delete', methods: ["DELETE"])]
    public function deleteProject(int $id): Response
    {
        $project = $this->projectService->getProjectById($id);
        if($project === null){ return $this->helperAction->jsonNotFound();}

        $this->projectService->deleteProject($project);
        return $this->json(
            [
                'result' => true,
                'error' => []
            ], Response::HTTP_OK);
    }
}
