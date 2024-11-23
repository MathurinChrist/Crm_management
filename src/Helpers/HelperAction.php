<?php

namespace App\Helpers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

class HelperAction  extends AbstractController
{
    public function handleErrors( ConstraintViolationList $violations): ?array
    {
        $errors = [];
        foreach ($violations->getIterator() as $violation) {
            $property = $violation->getPropertyPath();
            $message = $violation->getMessage();
            $errors[$property] = $message;
        }
        return $errors;
    }

    public function jsonNotFound(string $message = "Not Found"): Response
    {
        return $this->json(['result' => false, 'errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
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
    }
}

