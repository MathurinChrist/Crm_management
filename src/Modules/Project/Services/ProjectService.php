<?php

namespace App\Modules\Project\Services;

use App\Helpers\HelperAction;
use App\Modules\Project\Entity\Project;
use App\Modules\Project\Repository\ProjectRepository;
use App\Security\Entity\User;
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

    public function getAllProjects(User $user): ?array
    {
//        dd($this->projectRepository->getAllProjectsByUser($user));
//        return $this->projectRepository->findBy(['createdBy' => $user->getId()]);
        return $this->projectRepository->getAllProjectsByUser($user);
    }

    public function createProject(Project &$project, User $user): Project
    {
        HelperAction::SetCreateOrUpdateBy($project, $user);
        $this->entityManager->persist($project);
        $this->entityManager->flush();
        return $project;
    }

    public function updateProject(Project &$project): Project
    {
        $this->entityManager->flush();
        return $project;
    }

    public function deleteProject(Project &$project): void
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
}
