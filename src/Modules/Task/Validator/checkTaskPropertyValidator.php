<?php

namespace App\Modules\Task\Validator;

use App\Modules\Task\Entity\Task;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class checkTaskPropertyValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint): void
    {
        $value =  trim($value);
        if($value && strlen($value) > 1) { return; }
        $this->context->buildViolation($constraint->message)
            ->addViolation();
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