<?php

namespace App\Security\Event;

use App\Security\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class OnUserChangePassword extends Event
{
    public const NAME = 'onUserChangePassword';
    public function __construct(
        private readonly User $user,
        private readonly string $url,
    ){

    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUrl (): String
    {
        return $this->url;
    }


}
