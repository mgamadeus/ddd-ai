<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Benchmarks;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * A single agentic benchmark result for an AIModel — one (benchmark → score) datapoint with its citation.
 *
 * Held inside {@see AIModelBenchmarks} on {@see \DDD\Domain\AI\Entities\Models\AIModel}, parallel to the model's
 * cost/limit Settings. Scores are normalised to 0–100 (a percentage on the benchmark's own scale).
 *
 * Integrity rule: a real entry MUST carry its {@see $sourceUrl} (leaderboard/paper) and {@see $asOf} tag —
 * benchmark numbers drift and live behind volatile leaderboards, so a score without a cited, dated source is
 * not a valid entry (never copy a number from memory).
 *
 * Mirrors the {@see \DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariant} precedent (typed element VO inside
 * a typed ObjectSet, hydrated by AIModelsService) — arrays are not a substitute for value objects.
 */
class AIModelBenchmark extends ValueObject
{
    /** @var string Benchmark id — one of the {@see AIModelBenchmarks}::BENCHMARK_* constants (e.g. 'BFCL'). */
    public string $benchmark = '';

    /** @var float Normalised score on this benchmark, 0–100. */
    public float $score = 0.0;

    /** @var string|null Source the score was taken from (leaderboard URL / paper) — required for a real entry. */
    public ?string $sourceUrl = null;

    /** @var string|null As-of tag (ISO, e.g. '2026-05') — benchmarks drift, so every score is point-in-time. */
    public ?string $asOf = null;

    /**
     * @var bool Whether the score comes from the benchmark's OFFICIAL source (its own leaderboard/paper/provider
     *      model card) vs. a third-party AGGREGATOR (llm-stats, Artificial Analysis, pricepertoken, benchlm, …).
     *      Aggregators broaden coverage but disagree across scaffolds/harnesses → lower confidence. Default true.
     */
    public bool $official = true;

    public function __construct(
        string $benchmark = '',
        float $score = 0.0,
        ?string $sourceUrl = null,
        ?string $asOf = null,
        bool $official = true
    ) {
        $this->benchmark = $benchmark;
        $this->score = $score;
        $this->sourceUrl = $sourceUrl;
        $this->asOf = $asOf;
        $this->official = $official;
        parent::__construct();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->benchmark);
    }
}
