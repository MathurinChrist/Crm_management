<?php

namespace App\Modules\Task\Entity;

use App\Global\Validators\checkTaskProperty;
use App\Modules\Project\Entity\Project;
use App\Modules\Comments\Entity\Comments;
use App\Modules\Task\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    const statusOptions = ['todo', 'achieve', 'ok_prod','current'];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    public ?int $id = null;

    #[ORM\Column(length: 255)]
    #[checkTaskProperty]
    #[Groups(["task:read", "task:write", "task:create", "task:update", "project:read"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[checkTaskProperty]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    private ?string $context = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: Task::statusOptions, message: 'The value chosen is not valid')]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'task')]
    #[ORM\JoinColumn(name: 'project_id', nullable: false, onDelete: 'cascade')]
    #[Groups(["task:read"])]
    private Project $project;

    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'task', cascade: ['persist', 'remove'])]
    private Collection $comments;
    #[Groups(["task:read"])]
    private Comments $comment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): void
    {
        $this->comments->add($comment);
    }

    public function removeComment(Comments $comment): void
    {
        $this->comments->removeElement($comment);
    }


}
