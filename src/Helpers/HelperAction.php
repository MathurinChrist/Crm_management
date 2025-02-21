<?php

namespace App\Helpers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class HelperAction  extends AbstractController
{
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
        return $this->json(['result' => false, 'errors' => [$message]], $status);
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

