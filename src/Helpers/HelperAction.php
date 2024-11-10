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
        return $this->json(['result' => false, '$task' => null, 'errors' => ['Not found']], Response::HTTP_BAD_REQUEST);
    }

}