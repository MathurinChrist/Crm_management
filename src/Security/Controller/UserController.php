<?php

namespace App\Security\Controller;

use App\Helpers\HelperAction;

use App\Security\Entity\User;
use App\Security\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UserService $userService,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly HelperAction $helperAction,
        private readonly ValidatorInterface $validator
    ){

    }

    #[Route('/admin/signIn', name: 'registered', methods: ["POST"])]
    public function userRegistered(Request $request): ?Response
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $plaintextPassword = substr(str_shuffle(
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 16);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);
        $errors = $this->helperAction->handleErrors($this->validator->validate($user));
        $countErrors = count($errors);

        if ($countErrors === 0) {
            $this->userService->userRegistered($user, $plaintextPassword);
        }
        return $this->json([
            "errors" => $errors,
            "password" => $plaintextPassword,
        ], $countErrors === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
