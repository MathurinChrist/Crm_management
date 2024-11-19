<?php

namespace App\Modules\Project\Services;

use App\Modules\Project\Entity\Project;
use App\Modules\Project\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly EntityManagerInterface $entityManager
    ){}

    public function getAllProjects(): array
    {
        return $this->projectRepository->findAll();
    }

    public function createProject(Project &$project): Project
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $project;
    }

}