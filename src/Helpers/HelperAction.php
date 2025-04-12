<?php

namespace App\Helpers;

use App\Modules\Comments\Entity\Comment;
use App\Modules\Project\Entity\Project;
use App\Modules\Task\Entity\Task;
use App\Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Contracts\Translation\TranslatorInterface;

class HelperAction  extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ){
    }

    public static function getEnvVar(string $key): string|bool
    {
        //todo: must refacto this by using ParameterBagInterface
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === 'true') {
            $value = true;
        }
        if ($value === 'false') {
            $value = false;
        }
        return $value;
    }
    public static function handleErrors(ConstraintViolationList $violations): ?array
    {
        //token git without expiration date:  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
        $errors = [];
        foreach ($violations->getIterator() as $violation) {
            $property = $violation->getPropertyPath();
            $message = $violation->getMessage();
            $errors[$property] = $message;
        }
        return $errors;
    }

    public function jsonNotFoundOrError(string $message = "Not Found", int $status = Response::HTTP_NOT_FOUND): Response
    {
        $message = $this->translator->trans($message);
        return $this->json(['result' => false, 'errors' => [$message]], $status);
    }

    public static function SetCreateOrUpdateBy(Task|Project|Comment &$entity, User $user): void
    {
        $entity->setCreatedBy($user);
        $entity->setUpdatedBy($user);
    }

    public  function convertObject($obj): array
    {
        //this function  is to transform an object to array
        $reflectionClass = new \ReflectionClass($obj);
        $properties = $reflectionClass->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $array[$property->getName()] = $property->getValue($obj);
        }

        return $array;
        // bette to use a casting
    }
}

