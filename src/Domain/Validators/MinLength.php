<?php

namespace App\Domain\Validators;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class MinLength extends Constraint
{
    public string $message = 'two caracters are providing for this property.';
    public string $mode = 'strict';

    public function __construct(?string $message = null, ?array $groups = null, ?string $mode = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}
