<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Selection;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * Declarative model-selection policy (plan 08 §2) — the knobs {@see \DDD\Domain\AI\Services\AIModelsService} honors
 * when picking a model from the scored catalog: preferred tier band, soft vendor preference, optional cost ceiling,
 * interactivity, tool-calling requirement, and whether the curated agent-loop classification constrains the choice.
 * A DDD ValueObject (freely constructable, incl. unit tests).
 *
 * NO `autoMode` here — ADAPTIVE / runtime tier control is out of P0 scope (plan §5, separate flagged phase).
 */
class ModelManagerPolicy extends ValueObject
{
    /** @var string The operating band, a {@see ModelTier}::* constant. */
    public string $preferredTier;

    /** @var string|null Soft vendor preference (an AIModel::VENDOR_* value); never breaches the capability floor. */
    public ?string $preferredVendor;

    /** @var float|null Optional hard ceiling on blended $/1M tokens. */
    public ?float $maxBlendedCostPer1MUsd;

    /** @var bool When true, a minimum speed score may be enforced (interactive/chat use-case). */
    public bool $interactive;

    /**
     * @var bool Whether the selected model MUST support native tool-calling (the agentic default). Set false for
     *      NON-agentic scopes (compaction summary, memory/conversation-search rerank) where the model never calls
     *      a tool — otherwise the tool-calling hard gate wrongly excludes fast non-tool models (plan 28 §5).
     */
    public bool $requiresToolCalling;

    /**
     * @var bool Whether the curated agent-loop classification (the per-model `agentTier` membership AND the
     *      `agentEligible` gate) constrains selection. True for every AGENTIC/background scope (selection stays
     *      within the hand-vetted tier band). Set FALSE only for the FAST_CHEAP scope: a non-agentic speed pick
     *      MUST be able to reach agent-INELIGIBLE models (e.g. gpt-oss-120b @ Cerebras ≈526 tok/s — disqualified
     *      from the agent loop by its tool-calling channel leak, but the fastest cheap text generator we route to).
     *      When false, the agentTier filter + agentEligible gate are skipped and {@see $maxBlendedCostPer1MUsd}
     *      becomes the "cheap" boundary instead of tier membership.
     */
    public bool $appliesAgentLoopClassification;

    public function __construct(
        string $preferredTier,
        ?string $preferredVendor = null,
        ?float $maxBlendedCostPer1MUsd = null,
        bool $interactive = false,
        bool $requiresToolCalling = true,
        bool $appliesAgentLoopClassification = true,
    ) {
        parent::__construct();
        $this->preferredTier = $preferredTier;
        $this->preferredVendor = $preferredVendor;
        $this->maxBlendedCostPer1MUsd = $maxBlendedCostPer1MUsd;
        $this->interactive = $interactive;
        $this->requiresToolCalling = $requiresToolCalling;
        $this->appliesAgentLoopClassification = $appliesAgentLoopClassification;
    }
}
