<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * The price of a prompt-cache WRITE with one lifetime, as a multiple of the model's (uncached) input rate — e.g.
 * `'default' → 1.25`, `'12h' → 2.5` on OpenAI GPT-5.6+.
 *
 * Used inside {@see PromptCacheWritePrices} on {@see AILanguageModelSetting}.
 */
class PromptCacheWritePrice extends ValueObject
{
    /** @var string The write lifetime ({@see \DDD\Domain\AI\Entities\Models\AIModel::PROMPT_CACHE_WRITE_TTL_DEFAULT}, `12h`, …) */
    public string $ttl = '';

    /** @var float The write price as a multiple of the input rate */
    public float $priceMultiplier = 1.0;

    public function __construct(string $ttl = '', float $priceMultiplier = 1.0)
    {
        $this->ttl = $ttl;
        $this->priceMultiplier = $priceMultiplier;
        parent::__construct();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->ttl);
    }
}
