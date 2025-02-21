<?php

namespace App\Domain\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MinLengthValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint): void
    {
        $value = trim($value);
        if ($value && strlen($value) > 1) {
            return;
        }
        $message = $constraint->message ?? 'Two caracters are providing for this property';
        $this->context->buildViolation($message)
            ->addViolation();
    }
}
