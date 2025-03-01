<?php

namespace App\Domain\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;

class MinLengthValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        $value = trim($value);
        if ($value && strlen($value) > 1) {
            return;
        }
        $message = $this->translator->trans('global.min_length');
        $this->context->buildViolation($message)
            ->addViolation();
    }
}
