<?php

namespace App\Modules\Sso;

use App\Domain\Module\Module;
use App\Domain\Module\ModuleInterface;

class Sso extends Module implements ModuleInterface
{
    public static string $name = 'Sso';
    public static string $title = 'Sso';
    public static string $version = '0.0.1';
    public static string $phpVersion = '>=7.4.2';
    public static string $author = 'Crm management';
    public static string $description = '';
    public static array $roles = [];
    public static bool $isCore = false;
}
