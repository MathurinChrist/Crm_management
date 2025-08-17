<?php

namespace App\Security\Controller;

use App\Helpers\HelperAction;

use App\Security\Entity\User;
use App\Security\Repository\UserRepository;
use App\Security\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
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
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository
    ){
    }

    #[Route('/create', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): Response
    {
        /** @var User $user*/
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user_admin = $this->getUser();
        $data = json_decode($request->getContent(), true);

        $plaintextPassword = $data['password'];
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        $user->setCreatedBy($user_admin);
        $user->setUpdatedBy($user_admin);
        $user->setRoles(['ROLE_USER']);

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

    #[Route('/forgot-password', name: 'api_forgot_password', methods: ['POST'])]
    public function forgotPassword(Request $request, UserRepository $userRepo, MailerInterface $mailer, EntityManagerInterface $em): Response {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;

        if (!$email) return  $this->json(['error' => 'Email requis'], 400);

        $user = $userRepo->findOneBy(['email' => $email]);
        if (!$user) {
           return  $this->json(['result' => true, '[message => "Si un compte existe, un e-mail sera envoyé."])' => [] ], Response::HTTP_OK);
        }

        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $user->setResetTokenExpiresAt((new \DateTime())->modify('+1 hour'));
        $em->flush();
        $resetUrl = 'http://localhost:9000/#/authentication/password-reset?token=' . $token;

        $this->userService->senMessage($user, $resetUrl);
        return $this->json(['message' => 'Si un compte existe, un e-mail sera envoyé.']);
    }

    #[Route('/reset-password', name: 'api_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response {
        $data = json_decode($request->getContent(), true);
        $token = $data['token'] ?? null;
        $newPassword = $data['password'] ?? null;

        if (!$token || !$newPassword) return new JsonResponse(['error' => 'Token et mot de passe requis'], 400);

        $user = $userRepo->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
            return new JsonResponse(['error' => 'Token invalide ou expiré'], 400);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $user->setResetToken(null);
        $user->setResetTokenExpiresAt(null);
        $em->flush();

        return new JsonResponse(['message' => 'Mot de passe mis à jour avec succès.']);
    }



}
