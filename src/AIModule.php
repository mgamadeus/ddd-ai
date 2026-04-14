<?php

declare(strict_types=1);

namespace DDD\Modules\AI;

use DDD\Infrastructure\Modules\DDDModule;

class AIModule extends DDDModule
{
    public static function getSourcePath(): string
    {
        return __DIR__;
    }

    public static function getConfigPath(): ?string
    {
        return __DIR__ . '/../config/app';
    }

    public static function getPublicServiceNamespaces(): array
    {
        return [
            'DDD\\Domain\\AI\\Services\\',
            'DDD\\Domain\\Batch\\Services\\',
        ];
    }

    public static function getControllerPaths(): array
    {
        return [
            '/api/batch' => __DIR__ . '/Presentation/Api/Batch',
        ];
    }
}
