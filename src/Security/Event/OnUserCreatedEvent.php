<?php

namespace App\Security\Event;

use App\Security\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class OnUserCreatedEvent extends Event
{
    public const NAME = 'onUserCreated';
    public function __construct(
        private readonly User $user,
        private readonly string $password,
    ){

    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}
