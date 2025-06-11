<?php

namespace App\Modules\User\Controller;

use App\Security\Entity\User;
use App\Security\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserControler extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ){}

    #[Route('/user/me', name: 'menu', methods: ['GET'])]
    public function getUSerConnected(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json(['user' => $user,], Response::HTTP_OK, [], ['groups' => ['user:read']]);
    }

    #[Route('/users/all', name: '_list', methods: ['GET'])]
    public function getAllUser(): Response
    {
        $users = $this->userService->getUsers($this->getUser());
        return $this->json(
            [
                "results" => true,
                "total" => count($users),
                'users' => $users,
            ], Response::HTTP_OK, [], ['groups' => ['user:read']]
        );
    }
}
