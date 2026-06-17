<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Speed;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * A single output-speed measurement for an AIModel from one measurement platform — one
 * (source → tokens/sec [+ time-to-first-token]) datapoint with its citation.
 *
 * Held inside {@see AIModelSpeedMeasurements} on {@see \DDD\Domain\AI\Entities\Models\AIModel}, parallel to the
 * model's cost/limit Settings and its {@see \DDD\Domain\AI\Entities\Models\Benchmarks\AIModelBenchmarks}.
 *
 * Integrity rule (mirrors AIModelBenchmark): a real entry MUST carry its {@see $sourceUrl} and {@see $asOf} tag —
 * throughput fluctuates and lives behind volatile, traffic-window stats, so a number without a cited, dated source
 * is not a valid entry (never copy a number from memory).
 *
 * Mirrors the {@see \DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariant} / AIModelBenchmark precedent
 * (typed element VO inside a typed ObjectSet, hydrated by AIModelsService) — arrays are not a substitute for VOs.
 */
class AIModelSpeedMeasurement extends ValueObject
{
    /** @var string Measurement source — one of the {@see AIModelSpeedMeasurements}::SOURCE_* constants. */
    public string $source = '';

    /** @var float Median output throughput in tokens per second (the headline "speed" number). */
    public float $tokensPerSecond = 0.0;

    /** @var int|null Time-to-first-token in milliseconds, if the source publishes it. Semantics differ per source
     *      (e.g. Artificial Analysis counts the full reasoning phase for reasoning models). Nullable. */
    public ?int $timeToFirstTokenMs = null;

    /** @var string|null Source the measurement was taken from (re-fetchable stats endpoint / model page) — required. */
    public ?string $sourceUrl = null;

    /** @var string|null As-of tag (ISO, e.g. '2026-06-05') — throughput drifts, so every measurement is point-in-time. */
    public ?string $asOf = null;

    /**
     * The fields below carry the per-provider distribution that {@see AIModelSpeedMeasurements}::SOURCE_OPENROUTER_PROVIDERS
     * exposes (the OpenRouter model-page Providers payload, parsed per upstream provider). They are null for the
     * blended SOURCE_OPENROUTER / SOURCE_ARTIFICIAL_ANALYSIS datapoints. "Top" = the single best provider for that
     * metric (highest p50 throughput / lowest p50 latency — i.e. what `provider.sort` routing actually reaches);
     * "Avg" = the mean across all of the model's providers (the unrouted, you-get-what-you-get expectation).
     */

    /** @var int|null Number of upstream providers serving this model on OpenRouter (the spread the routing chooses from). */
    public ?int $providerCount = null;

    /** @var string|null Name of the highest-throughput provider (e.g. 'Cerebras') — what `provider.sort:throughput` reaches. */
    public ?string $topThroughputProvider = null;

    /** @var string|null Name of the lowest-latency provider (e.g. 'Groq') — what `provider.sort:latency` reaches. */
    public ?string $topLatencyProvider = null;

    /** @var SpeedPercentiles|null Throughput (tok/s) percentiles of the FASTEST provider — the speed-routed expectation. */
    public ?SpeedPercentiles $throughputTopPercentiles = null;

    /** @var SpeedPercentiles|null Throughput (tok/s) percentiles averaged across ALL providers — the unrouted expectation. */
    public ?SpeedPercentiles $throughputAvgPercentiles = null;

    /** @var SpeedPercentiles|null Latency / time-to-first-token (ms) percentiles of the LOWEST-latency provider. */
    public ?SpeedPercentiles $latencyTopPercentiles = null;

    /** @var SpeedPercentiles|null Latency / time-to-first-token (ms) percentiles averaged across ALL providers. */
    public ?SpeedPercentiles $latencyAvgPercentiles = null;

    public function __construct(
        string $source = '',
        float $tokensPerSecond = 0.0,
        ?int $timeToFirstTokenMs = null,
        ?string $sourceUrl = null,
        ?string $asOf = null,
        ?int $providerCount = null,
        ?string $topThroughputProvider = null,
        ?string $topLatencyProvider = null,
        ?SpeedPercentiles $throughputTopPercentiles = null,
        ?SpeedPercentiles $throughputAvgPercentiles = null,
        ?SpeedPercentiles $latencyTopPercentiles = null,
        ?SpeedPercentiles $latencyAvgPercentiles = null
    ) {
        $this->source = $source;
        $this->tokensPerSecond = $tokensPerSecond;
        $this->timeToFirstTokenMs = $timeToFirstTokenMs;
        $this->sourceUrl = $sourceUrl;
        $this->asOf = $asOf;
        $this->providerCount = $providerCount;
        $this->topThroughputProvider = $topThroughputProvider;
        $this->topLatencyProvider = $topLatencyProvider;
        $this->throughputTopPercentiles = $throughputTopPercentiles;
        $this->throughputAvgPercentiles = $throughputAvgPercentiles;
        $this->latencyTopPercentiles = $latencyTopPercentiles;
        $this->latencyAvgPercentiles = $latencyAvgPercentiles;
        parent::__construct();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->source);
    }
}
