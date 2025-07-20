<?php

namespace App\Modules\Sso\Provider;

interface SsoProviderInterface
{
    public function getClientKey(): string;
    public function getScopes(): array;
    public function fetchUserData(array $token): array;
}
