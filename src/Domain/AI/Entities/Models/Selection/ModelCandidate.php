<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Selection;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * A lightweight projection of an AIModel for the selection algorithm — the four decision axes (capability, speed,
 * input/output price) plus identity. A DDD ValueObject so the pure pipeline can be unit-tested against a fixed
 * snapshot of synthetic candidates WITHOUT constructing heavy AIModel entities. {@see \DDD\Domain\AI\Services\AIModelsService}
 * builds these from the live catalog.
 *
 * blendedCost / valuePerDollar are derived, not stored — a candidate is pure data.
 */
class ModelCandidate extends ValueObject
{
    /** @var string The AIModel name (e.g. 'GOOGLE.GEMINI_3_1_PRO_PREVIEW'). */
    public string $name;

    /** @var string|null AIModel::VENDOR_* value. */
    public ?string $vendor;

    /** @var int|null Agentic-intelligence score (null = unrated → never auto-selected). */
    public ?int $agenticScore;

    /** @var int|null Normalised 0–100 speed score (null = unmeasured). */
    public ?int $speedScore;

    /** @var float|null Canonical output throughput in tokens/sec (null = unmeasured). */
    public ?float $tokensPerSecond;

    /** @var float Input price, USD per 1M tokens. */
    public float $inputCostPer1MUsd;

    /** @var float Output price, USD per 1M tokens. */
    public float $outputCostPer1MUsd;

    /** @var int|null Max input tokens (context window). Null = unknown. */
    public ?int $maxInputTokens;

    /** @var int|null Practically usable input tokens (the realistic, output-reserving bound). Null = unknown. */
    public ?int $maxPracticallyUsableInputTokens;

    /** @var bool Whether the model does reliable native tool-calling via the OpenAI-chat egress (false → excluded
     *      from agentic selection; e.g. gpt-oss leaks harmony channel markers into tool names). */
    public bool $supportsNativeToolCalling;

    /** @var string|null Curated agentic tier (a {@see ModelTier}::* value), or null = not classified for the
     *      agent loop. The single source of truth for tier membership (set per-model in models.php). */
    public ?string $agentTier;

    /** @var bool Whether the model is eligible for the agent loop at all (false → hard-disqualified, never
     *      selected; e.g. fabricates or can't complete a tool chain). Distinct from tier membership. */
    public bool $agentEligible;

    /** @var int|null Time to first token in milliseconds (latency before output starts; null = unmeasured). */
    public ?int $timeToFirstTokenMs;

    public function __construct(
        string $name,
        ?string $vendor,
        ?int $agenticScore,
        ?int $speedScore,
        ?float $tokensPerSecond,
        float $inputCostPer1MUsd,
        float $outputCostPer1MUsd,
        ?int $maxInputTokens = null,
        ?int $maxPracticallyUsableInputTokens = null,
        bool $supportsNativeToolCalling = true,
        ?string $agentTier = null,
        bool $agentEligible = true,
        ?int $timeToFirstTokenMs = null,
    ) {
        parent::__construct();
        $this->name = $name;
        $this->vendor = $vendor;
        $this->agenticScore = $agenticScore;
        $this->speedScore = $speedScore;
        $this->tokensPerSecond = $tokensPerSecond;
        $this->inputCostPer1MUsd = $inputCostPer1MUsd;
        $this->outputCostPer1MUsd = $outputCostPer1MUsd;
        $this->maxInputTokens = $maxInputTokens;
        $this->maxPracticallyUsableInputTokens = $maxPracticallyUsableInputTokens;
        $this->supportsNativeToolCalling = $supportsNativeToolCalling;
        $this->agentTier = $agentTier;
        $this->agentEligible = $agentEligible;
        $this->timeToFirstTokenMs = $timeToFirstTokenMs;
    }

    /**
     * The input-window size to constrain against: the practical bound if known, else the raw max, else null
     * (unknown — can't verify the task fits). Context is a hard threshold, not an optimization axis.
     */
    public function effectiveInputWindow(): ?int
    {
        if ($this->maxPracticallyUsableInputTokens !== null && $this->maxPracticallyUsableInputTokens > 0) {
            return $this->maxPracticallyUsableInputTokens;
        }
        if ($this->maxInputTokens !== null && $this->maxInputTokens > 0) {
            return $this->maxInputTokens;
        }
        return null;
    }

    /**
     * Blended cost in USD per 1M tokens. Agentic workloads are input-heavy (tool results / context), so input is
     * weighted higher by default. Weights are passed in by the caller (a single tunable constant on the service).
     */
    public function blendedCostPer1MUsd(float $inputWeight, float $outputWeight): float
    {
        return $inputWeight * $this->inputCostPer1MUsd + $outputWeight * $this->outputCostPer1MUsd;
    }

    /**
     * Agentic-score-per-blended-dollar (intelligence per dollar). Null when unrated or free/zero-cost
     * (division undefined — handled by the caller).
     */
    public function valuePerDollar(float $inputWeight, float $outputWeight): ?float
    {
        if ($this->agenticScore === null) {
            return null;
        }
        $cost = $this->blendedCostPer1MUsd($inputWeight, $outputWeight);
        if ($cost <= 0.0) {
            return null;
        }
        return $this->agenticScore / $cost;
    }
}
