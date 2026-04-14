<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Prompts;

use DDD\Domain\AI\Services\AIPromptsService;
use DDD\Domain\Base\Entities\EntitySet;

/**
 * @property AIPrompt[] $elements;
 * @method AIPrompt|null first()
 * @method AIPrompt|null getByUniqueKey(string $uniqueKey)
 * @method AIPrompt[] getElements()
 * @method static AIPromptsService getService()
 */
class AIPrompts extends EntitySet
{
    public const string SERVICE_NAME = AIPromptsService::class;
}
