<?php

namespace App\Modules\Project\Entity;
use Doctrine\ORM\Mapping as ORM;


class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[ORM\Column]
    private ?int $count_number_task = null;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function countNumberTask(): ?int
    {
        return $this->count_number_task;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function setCountNumberTask(?int $count_number_task): void
    {
        $this->count_number_task = $count_number_task;
    }


}