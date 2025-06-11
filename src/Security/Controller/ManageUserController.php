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
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user', name: 'manage_user_')]
class ManageUserController extends AbstractController
{
    public function __construct(
        public readonly SerializerInterface $serializer,
        public readonly UserPasswordHasherInterface $passwordHasher,
        public readonly HelperAction $helperAction,
        public readonly ValidatorInterface $validator,
        public readonly UserService $userService,
        private readonly TranslatorInterface $translator
    ){
    }

    #[Route('/admin/create', name: 'create', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        /** @var User $user*/
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user_admin = $this->getUser();
        $plaintextPassword = substr(str_shuffle(
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 16);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $user->setCreatedBy($user_admin);
        $user->setUpdatedBy($user_admin);

        $errors = $this->helperAction->handleErrors($this->validator->validate($user));
        $countErrors = count($errors);

        if ($countErrors === 0) {
            $this->userService->userRegistered($user, $plaintextPassword);
        }

        return $this->json([
            "errors" => $errors,
            "password" => $plaintextPassword,
            "user"=> $user
        ], $countErrors === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST, [], ['groups' => ['user:read']]);
    }

    #[Route('/admin/delete/{user}', name: 'delete', methods: ['DELETE'])]
    public function deleteUser(?User $user): Response
    {
        if ($user === null || $user === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('user_module.not_found'));
        }
        $this->userService->deleteUser($user);
        return $this->json(['result' => true, 'error' => [] ], Response::HTTP_OK);
    }

    #[Route('/update/{user}', name: 'update', methods: ['PUT'])]
    public function updateUser(Request $request, ?User $user): Response
    {
        if ($user === null || $user === null) {
            return $this->helperAction->jsonNotFoundOrError($this->translator->trans('user_module.not_found'));
        }
        $ignoredAttributes = ['password', 'email', 'createdBy'];

        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $ignoredAttributes[] = 'roles';
        }
        $this->serializer->deserialize($request->getContent(), User::class, 'json',
            [
                'groups' => ["user:read"],
                AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredAttributes,
                AbstractNormalizer::OBJECT_TO_POPULATE => $user
            ]
        );

        $user->setUpdatedBy($this->getUser());
        $errors = $this->helperAction->handleErrors($this->validator->validate($user));
        $countErrors = count($errors);

        if ($countErrors === 0) {
            $this->userService->updateUser($user);
        }

        return $this->json([
            "errors" => $errors,
            "user"=> $user
        ], $countErrors === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST, [], ['groups' => ['user:read']]);
    }

}
