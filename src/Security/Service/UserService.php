<?php

namespace App\Security\Service;

use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {

    }

    public function userRegistered(User &$user): void
    {
        $user->setRoles(['ROLE_USER']);
        //todo manage sending email for user which wil contain his password random
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
