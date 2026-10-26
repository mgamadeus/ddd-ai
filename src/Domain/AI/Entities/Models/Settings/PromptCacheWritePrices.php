<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Base\Entities\ObjectSet;

/**
 * @method PromptCacheWritePrice first()
 * @method PromptCacheWritePrice getByUniqueKey(string $uniqueKey)
 * @method PromptCacheWritePrice[] getElements()
 * @property PromptCacheWritePrice[] $elements
 */
class PromptCacheWritePrices extends ObjectSet
{
    /** The write price multiple for $ttl; null when the set has no entry for it. */
    public function getPriceMultiplierForTtl(string $ttl): ?float
    {
        foreach ($this->getElements() as $promptCacheWritePrice) {
            if ($promptCacheWritePrice->ttl === $ttl) {
                return $promptCacheWritePrice->priceMultiplier;
            }
        }
        return null;
    }
}
