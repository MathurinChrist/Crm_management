<?php

namespace App\Modules\checkListTask\Entity;

use App\Modules\Task\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class ChecklistItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["task:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["task:read", "task:write", "task:update"])]
    private string $text;

    #[ORM\Column(type: "boolean")]
    #[Groups(["task:read", "task:write", "task:update"])]
    private bool $completed = false;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'checklist')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Task $task;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): void
    {
        $this->task = $task;
    }
}
