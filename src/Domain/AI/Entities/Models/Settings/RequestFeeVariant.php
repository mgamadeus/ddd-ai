<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Base\Entities\ValueObject;
use DDD\Domain\Common\Entities\Money\MoneyAmount;

/**
 * Per-request fee variant for AI language models. Holds a single
 * (variant-key → fee-amount) pair, e.g. `'search_context_size.low' → $0.005`.
 *
 * Used inside {@see RequestFeeVariants} on {@see AILanguageModelSetting} to
 * carry pricing-variant-keyed flat fees that some vendors charge per request
 * (Perplexity Sonar's `search_context_size.*` is the canonical example).
 */
class RequestFeeVariant extends ValueObject
{
    /** @var string Variant key (e.g. 'search_context_size.medium') */
    public string $variant = '';

    /** @var MoneyAmount Flat fee charged when the request uses this variant */
    public MoneyAmount $amount;

    public function __construct(string $variant = '', ?MoneyAmount $amount = null)
    {
        $this->variant = $variant;
        $this->amount = $amount ?? new MoneyAmount();
        parent::__construct();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->variant);
    }
}
