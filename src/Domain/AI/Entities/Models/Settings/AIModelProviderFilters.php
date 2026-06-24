<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * Per-model OpenRouter PROVIDER-ROUTING filter, scoped to the OpenRouter / LiteLLM-proxy egress only. When a model
 * entry carries one in the catalog, the OpenRouter egress MERGES it into the request's `provider` block via
 * {@see self::applyToProviderBlock()}, composing with (and taking precedence over) the dynamic
 * `sort`/`require_parameters` the egress adds. Absent → OpenRouter picks the provider freely.
 *
 * Why this exists: some models leak / misbehave on a SPECIFIC upstream provider, so a model must be able to declare
 * "route me to provider X, never fall back". The canonical case is Qwen3-235B-Instruct, whose tool-calls leak as plain
 * text ~30 % of the time on the Google (Vertex) endpoint but 0 % on DeepInfra (measured) — see the catalog comment on
 * that model.
 *
 * Field semantics map 1:1 to OpenRouter's `provider` routing keys:
 *  - `order`          — provider PRIORITY list (lowercase OpenRouter slugs, e.g. `deepinfra`, `google-vertex`). This is
 *                       the one our LiteLLM proxy actually honors (it STRIPS `only`); `order` even overrides the
 *                       proxy's tools→Google default. With a single slug + `allowFallbacks:false` it is a hard pin.
 *  - `ignore`         — providers to skip entirely.
 *  - `allowFallbacks` — false = never silently fall back to a provider outside `order` (a wrong slug then errors with
 *                       OpenRouter "No endpoint", which is the correct loud failure for a misconfigured pin).
 */
class AIModelProviderFilters extends ValueObject
{
    /**
     * @var array<int, string> Provider priority list (OpenRouter slugs). Empty = no `order` directive.
     */
    public array $order = [];

    /**
     * @var array<int, string> Providers to skip. Empty = no `ignore` directive.
     */
    public array $ignore = [];

    /**
     * @var bool|null false = no fallback outside `order`; null = leave OpenRouter's default (fallbacks allowed).
     */
    public ?bool $allowFallbacks = null;

    /**
     * @param array<int, string> $order
     * @param array<int, string> $ignore
     */
    public function __construct(array $order = [], array $ignore = [], ?bool $allowFallbacks = null)
    {
        $this->order = $order;
        $this->ignore = $ignore;
        $this->allowFallbacks = $allowFallbacks;
        parent::__construct();
    }

    /**
     * Merge THIS filter's directives into an existing OpenRouter `provider` block (which may already carry
     * `sort`/`require_parameters`). The directives take precedence over `sort` (OpenRouter applies `order` first;
     * verified: `order:[deepinfra]` + `sort:throughput` → DeepInfra). Only non-empty directives are written, so an
     * empty filter is a no-op.
     *
     * @param array<string, mixed> $providerBlock
     * @return array<string, mixed>
     */
    public function applyToProviderBlock(array $providerBlock): array
    {
        if ($this->order !== []) {
            $providerBlock['order'] = $this->order;
        }
        if ($this->ignore !== []) {
            $providerBlock['ignore'] = $this->ignore;
        }
        if ($this->allowFallbacks !== null) {
            $providerBlock['allow_fallbacks'] = $this->allowFallbacks;
        }
        return $providerBlock;
    }

    /**
     * Whether this filter carries any directive at all (else applying it is a no-op).
     */
    public function hasAnyDirective(): bool
    {
        return $this->order !== [] || $this->ignore !== [] || $this->allowFallbacks !== null;
    }
}
