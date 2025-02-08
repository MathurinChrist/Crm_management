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

    public function getProjectById(int $id): ?Project
    {
        return $this->projectRepository->find($id);
    }

    public function getProjectByName(?string $name): ?Project
    {
        return $this->projectRepository->findOneBy(['name' => $name]);
    }

    public function getAllProjects(): ?array
    {
        return $this->projectRepository->findAll();
    }

    public function createProject(Project &$project): Project
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $project;
    }

    public function updateProject(Project &$project): void
    {
        $this->entityManager->persist($project);
        $this->entityManager->flush();
    }

    public function deleteProject(Project &$project): void
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}
