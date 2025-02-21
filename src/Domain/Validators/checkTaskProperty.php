<?php

namespace App\Domain\Validators;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class checkTaskProperty extends Constraint
{
    public string $message = 'two caracters are providing for this property.';
    public string $mode = 'strict';

    // all configurable options must be passed to the constructor
    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }
}
