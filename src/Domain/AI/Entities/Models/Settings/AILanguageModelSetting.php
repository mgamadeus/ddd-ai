<?php

declare (strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Common\Entities\Money\MoneyAmount;

class AILanguageModelSetting extends AIModelSetting
{
    /** @var int Max total tokens supported (Input + Output) */
    public int $maxTokens;

    /** @var int Max Input Tokens */
    public int $maxInputTokens;

    /** @var int Max Output Tokens */
    public int $maxOutputTokens;

    /** @var int Max input tokens that are practically usable without timeouts */
    public int $maxPracticallyUsableInputTokens;

    /** @var MoneyAmount Costs per 10K Input Tokens */
    public MoneyAmount $costsPer1000KInputTokens;

    /** @var MoneyAmount Costs per 10K Output Tokens */
    public MoneyAmount $costsPer1000KOutputTokens;

    // Optional tiered pricing (use when a model like Gemini 2.5 Pro has tiers)
    /** @var int|null Prompt-size threshold (in tokens) at which pricing changes */
    public ?int $inputTierThresholdTokens = 200000;

    /** @var MoneyAmount|null Input cost per 1K tokens when prompt > threshold */
    public ?MoneyAmount $costsPer1000InputTokensAboveThreshold = null;

    /** @var MoneyAmount|null Output cost per 1K tokens when prompt > threshold */
    public ?MoneyAmount $costsPer1000OutputTokensAboveThreshold = null;

    /** @var MoneyAmount|null Costs per 1K cached input tokens (prompt-prefix cache discount) */
    public ?MoneyAmount $costsPer1000KCachedInputTokens = null;

    /** @var MoneyAmount|null Flat fee per web search tool invocation */
    public ?MoneyAmount $costsPerWebSearchCall = null;

    /**
     * Per-request fees keyed by pricing variant (e.g. 'search_context_size.low')
     * carried as a typed {@see RequestFeeVariants} ObjectSet of {@see RequestFeeVariant}
     * value objects — not a plain `array<string, MoneyAmount>` — per the framework's
     * "Arrays Are Not a Substitute for ValueObjects / ObjectSets" convention.
     *
     * Nullable + default null: only allocate when the configured model actually has
     * variant-keyed fees (most don't). Service hydration in {@see AIModelsService}
     * lazy-allocates on first variant.
     */
    public ?RequestFeeVariants $costsPerRequestVariants = null;

    /**
     * Returns the flat per-request fee for the given pricing variant, or null when
     * the variant is not configured on this model.
     */
    public function getRequestFeeForVariant(string $variant): ?MoneyAmount
    {
        if (!isset($this->costsPerRequestVariants)) {
            return null;
        }
        foreach ($this->costsPerRequestVariants->getElements() as $entry) {
            if ($entry->variant === $variant) {
                return $entry->amount;
            }
        }
        return null;
    }
}