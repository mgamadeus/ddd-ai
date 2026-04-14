<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models;

use DDD\Domain\AI\Services\AIModelsService;
use DDD\Domain\Base\Entities\EntitySet;

/**
 * @property AIModel[] $elements;
 * @method AIModel|null first()
 * @method AIModel|null getByUniqueKey(string $uniqueKey)
 * @method AIModel[] getElements()
 * @method static AIModelsService getService()
 */
class AIModels extends EntitySet
{
}
