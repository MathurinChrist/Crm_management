<?php

namespace App\Security\Service;

use App\Security\Entity\User;
use App\Security\Event\OnUserCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ){
    }

    public function userRegistered(User &$user, string $plaintextPassword): void
    {
        $user->setRoles(['ROLE_USER']);
        //todo manage sending email for user which wil contain his password random
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new OnUserCreatedEvent($user, $plaintextPassword));
    }
}
