<?php

namespace App\Modules\Task\Entity;

use App\Modules\Task\Repository\TaskRepository;
use App\Modules\Task\Validator\checkTaskProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    //todo: back to this class to do some relations with others classes
    const statusOptions = ['todo', 'achieve', 'ok_prod','current'];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[checkTaskProperty]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[checkTaskProperty]
    private ?string $context = null;

    #[ORM\Column(length: 255)]
    private ?string $project_to = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: Task::statusOptions, message: 'The value chosen is not valid')]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getProjectTo(): ?string
    {
        return $this->project_to;
    }

    public function setProjectTo(string $project_to): static
    {
        $this->project_to = $project_to;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

}
