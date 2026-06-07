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

    public function __construct(
        string $source = '',
        float $tokensPerSecond = 0.0,
        ?int $timeToFirstTokenMs = null,
        ?string $sourceUrl = null,
        ?string $asOf = null
    ) {
        $this->source = $source;
        $this->tokensPerSecond = $tokensPerSecond;
        $this->timeToFirstTokenMs = $timeToFirstTokenMs;
        $this->sourceUrl = $sourceUrl;
        $this->asOf = $asOf;
        parent::__construct();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->source);
    }
}
