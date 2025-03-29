<?php

namespace App\Modules\Project\Entity;

use App\Domain\Validators\MinLength;
use App\Entity\Traits\Timestampable;
use App\Entity\Traits\UserTrait;
use App\Modules\Project\Repository\ProjectRepository;
use App\Modules\Task\Entity\Task;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['name'], message: 'project_module.allready_esist')]
class Project
{
    use Timestampable;
    use UserTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["task:read", "task:write", "project:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["project:read", "project:create", "project:update"])]
    #[MinLength]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["project:read", "project:create", "project:update"])]
    private ?string $description = null;

    #[Groups(["project:read"])]
    private ?int $tasksNumber = 0;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
    private Collection $task;

    public function __construct()
    {
        $this->task = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTask(): ?Collection
    {
        return $this->task;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function removeTask(Task $task): void
    {
        if ($this->task->contains($task)) {
            $this->task->removeElement($task);
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }
    }

    public function addTask(Task $task): self
    {
        if (!$this->task->contains($task)) {
            $this->task[] = $task;
            $task->setProject($this);
        }
        return $this;

    }

    public function getTasksNumber(): ?int
    {
        return $this->task->count();
    }
}
