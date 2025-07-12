<?php

namespace App\Modules\Task\Entity;

use App\Domain\Validators\checkTaskProperty;
use App\Entity\Traits\Timestampable;
use App\Entity\Traits\UserTrait;
use App\Modules\checkListTask\Entity\ChecklistItem;
use App\Modules\Project\Entity\Project;
use App\Modules\Comments\Entity\Comment;
use App\Modules\Task\Repository\TaskRepository;
use App\Security\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['title'], message: 'task_message.allready_exist')]
class Task
{
    use Timestampable;
    use UserTrait;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->assignedUsers = new ArrayCollection();
        $this->checklist = new ArrayCollection();
    }

    const statusOptions = ['todo', 'done', 'ok_prod','current','in_progress'];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["task:update", "project:read", "task:read"])]
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
    private ?string $status = "todo";

    #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'task')]
    #[ORM\JoinColumn(name: 'project_id', nullable: false, onDelete: 'cascade')]
    #[Groups(["task:read"])]
    private Project $project;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'tasks', cascade: ['persist', 'remove'])]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    private Collection $assignedUsers;
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'task', cascade: ['persist', 'remove'])]
    #[Groups(["task:read"])]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: ChecklistItem::class, mappedBy: 'task', cascade: ['persist', 'remove'],  orphanRemoval: true)]
    #[Groups(["task:read", "task:write", "task:update", "project:read"])]
    private Collection $checklist;
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

    public function getAssignedUsers(): Collection
    {
        return $this->assignedUsers;
    }

    public function getChecklist(): Collection
    {
        return $this->checklist;
    }

    public function addAssignedUser(User $user): self
    {
        if (!$this->assignedUsers->contains($user)) {
            $this->assignedUsers[] = $user;
        }

        return $this;
    }

    public function removeAssignedUser(User $user): self
    {
        if ($this->assignedUsers->removeElement($user)) {
            $user->removeTask($this);
        }

        return $this;
    }

    public function addChecklistItem(ChecklistItem $item): void
    {
        if (!$this->checklist->contains($item)) {
            $this->checklist->add($item);
            $item->setTask($this);
        }
    }

    public function removeChecklistItem(ChecklistItem $item): void
    {
        if ($this->checklist->removeElement($item)) {
            if ($item->getTask() === $this) {
                $item->setTask(null);
            }
        }
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

    public function addComment(Comment $comment): void
    {
        $this->comments->add($comment);
    }

    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }
}
