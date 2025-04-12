<?php

namespace App\Factory;

use App\Security\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailingFactory
{
    public function createTemplateEmail (): TemplatedEmail
    {
        return new TemplatedEmail();
    }

}