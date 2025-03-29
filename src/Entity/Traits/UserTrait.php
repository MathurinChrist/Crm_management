<?php

namespace App\Entity\Traits;

use App\Security\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait UserTrait
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "createdBy", referencedColumnName: "id", nullable: false)]
    #[Groups(['project:read', 'user:read'])]
    private ?User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "updatedBy", referencedColumnName: "id", nullable: false)]
    #[Groups(['project:read', 'user:read'])]
    private ?User $updatedBy;

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $user): void
    {
        $this->createdBy = $user;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $user): void
    {
        $this->updatedBy = $user;
    }
}
