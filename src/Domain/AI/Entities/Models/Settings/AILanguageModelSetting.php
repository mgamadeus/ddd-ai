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
}