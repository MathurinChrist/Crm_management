<?php

namespace App\Global\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class checkTaskPropertyValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint): void
    {
        $value =  trim($value);
        if($value && strlen($value) > 1) { return; }
        $message = $constraint->message ?? 'thow caracters are required for this';
        $this->context->buildViolation($message)
            ->addViolation();
    }
}
