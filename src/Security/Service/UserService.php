<?php

namespace App\Security\Service;

use App\Security\Entity\User;
use App\Security\Event\OnUserCreatedEvent;
use App\Security\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserRepository $userRepository
    ){
    }

    public function userRegistered(User &$user, string $plaintextPassword): void
    {
        $this->entityManager->persist($user);
        $this->eventDispatcher->dispatch(new OnUserCreatedEvent($user, $plaintextPassword));
        $this->entityManager->flush();
    }

    public function updateUser (User &$user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function deleteUser (User &$user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function getUsers  ($user) : ?array
    {
        /** @var User $user */
        $id_admin = $user->getCreatedBy() === null ? $user->getId() : $user->getCreatedBy()->getId();
        $allUsers = $this->userRepository->findUsersCreatedBy($id_admin) ?? [];

        if ($user->getCreatedBy() === null) {
            $allUsers[] = $user;
        }
        return $allUsers;
    }

    public function getUserById (int $id) : ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }
}
