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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    #[Route('/updateProfile/{user}', name: 'updat_user_profile', methods: ["POST"])]
    public function updateUserProfile(Request $request, ?User $user): ?Response
    {
        if ($user->getId() === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('project_module.not_found'));
        }
        /** @var User $user */
        $user = $this->getUser();
        $this->serializer->deserialize($request->getContent(), User::class, 'json',
            [
                'groups' => ['user:read', 'user:update'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['email', 'user_type', 'roles'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $user
            ]
        );

        $result = false;
        $errors = $this->helperAction->handleErrors($this->validator->validate($user));
        if (count($errors) === 0) {
            $result = true;
            $this->userService->updateUser($user);
        }

        return $this->json([
            'result' => $result,
            'data' => $user,
            'error' => $errors
        ], $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST, [], ['groups' => ['user:read']]);
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
