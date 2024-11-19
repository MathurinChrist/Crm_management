<?php

namespace App\Modules\Project\Controller;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Project\Services\ProjectService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/project', name: 'project')]
class ProjectController extends AbstractController
{
    public function __construct(
//        private readonly HelperAction $helperAction,
        private readonly ProjectService $projectService,
        private readonly SerializerInterface $serializer

    ){}

    #[Route('/allProject', name: '_all',methods: ["GET"])]
    public function getAllProjects(): Response
    {
        //todo check roles and autho....
        $allProjects = $this->projectService->getAllProjects();
//        dd($allProjects);
        return $this->json(['result' => true,
            'total' => count($allProjects),
            'data' => $allProjects,
            'error' => []], Response::HTTP_OK, []);
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

}