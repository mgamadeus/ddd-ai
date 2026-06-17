<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Speed;

use DDD\Domain\Base\Entities\ObjectSet;

/**
 * Typed set of {@see AIModelSpeedMeasurement} datapoints for an AIModel — the output-speed evidence stored parallel
 * to the model's cost/limit Settings and its {@see \DDD\Domain\AI\Entities\Models\Benchmarks\AIModelBenchmarks}.
 * Mirrors {@see \DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariants}.
 *
 * A model may carry one measurement per SOURCE (keyed by {@see AIModelSpeedMeasurement::uniqueKey()} = source).
 * {@see SOURCE_OPENROUTER} is the CANONICAL source for the headline speed value/score, because we route models via
 * `openrouter/<slug>` — OpenRouter's measured live p50 throughput is the operationally relevant number for us.
 * {@see SOURCE_ARTIFICIAL_ANALYSIS} is kept as a controlled-benchmark cross-check.
 *
 * The two sources measure differently (OpenRouter = p50 over a rolling live-traffic window incl. our routing path;
 * Artificial Analysis = controlled vendor-API benchmark), and time-to-first-token semantics diverge for reasoning
 * models — so both are stored with provenance rather than blended.
 *
 * @method AIModelSpeedMeasurement first()
 * @method AIModelSpeedMeasurement getByUniqueKey(string $uniqueKey)
 * @method AIModelSpeedMeasurement[] getElements()
 * @property AIModelSpeedMeasurement[] $elements
 */
class AIModelSpeedMeasurements extends ObjectSet
{
    public const string SOURCE_OPENROUTER = 'OPENROUTER';

    public const string SOURCE_ARTIFICIAL_ANALYSIS = 'ARTIFICIAL_ANALYSIS';

    /**
     * Per-provider distribution parsed from the OpenRouter model-page Providers payload: for every upstream provider
     * serving the model, the p50/p75/p90/p99 throughput + latency over the 30-minute window, aggregated into TOP
     * (best provider) and AVG (mean across providers). This is the CANONICAL source when present, because we route
     * with `provider.sort`/`order` — the speed we actually get is the TOP provider's, not the cross-provider blend
     * that {@see SOURCE_OPENROUTER} reports. Carries the {@see SpeedPercentiles} bundles on the measurement.
     */
    public const string SOURCE_OPENROUTER_PROVIDERS = 'OPENROUTER_PROVIDERS';

    /**
     * @var float Reference output throughput (tokens/sec) that maps to a normalised speed score of 100. Chosen as a
     *      "fast interactive generation" rate on OpenRouter's p50 throughput scale; tokens/sec at or above this map
     *      to 100. Tune this single constant as the fleet's throughput profile shifts — the score is a pure function
     *      of it, so the whole ranking rescales consistently.
     */
    public const float REFERENCE_TOKENS_PER_SECOND_FOR_FULL_SCORE = 150.0;

    /**
     * The canonical measurement for headline speed, preferring (in order): {@see SOURCE_OPENROUTER_PROVIDERS} (the
     * provider-resolved top throughput — what `provider.sort` routing actually reaches), then {@see SOURCE_OPENROUTER}
     * (the cross-provider blend), then the first available measurement. Returns null when the set is empty.
     */
    public function getCanonicalMeasurement(): ?AIModelSpeedMeasurement
    {
        $first = null;
        $openRouterBlend = null;
        foreach ($this->getElements() as $measurement) {
            if ($first === null) {
                $first = $measurement;
            }
            if ($measurement->source === self::SOURCE_OPENROUTER_PROVIDERS) {
                return $measurement;
            }
            if ($measurement->source === self::SOURCE_OPENROUTER) {
                $openRouterBlend = $measurement;
            }
        }
        return $openRouterBlend ?? $first;
    }

    /**
     * Canonical output throughput in tokens/sec (OpenRouter-preferred). Null when no measurement is present.
     */
    public function getTokensPerSecond(): ?float
    {
        return $this->getCanonicalMeasurement()?->tokensPerSecond;
    }

    /**
     * Canonical time-to-first-token in milliseconds (OpenRouter-preferred). Null when unavailable.
     */
    public function getTimeToFirstTokenMs(): ?int
    {
        return $this->getCanonicalMeasurement()?->timeToFirstTokenMs;
    }

    /**
     * Normalised 0–100 speed score from the canonical tokens/sec, linearly scaled against
     * {@see REFERENCE_TOKENS_PER_SECOND_FOR_FULL_SCORE} and capped at 100. Distinct from raw tokens/sec (absolute)
     * and from the agentic-intelligence score. Returns null when no speed measurement is present.
     */
    public function getSpeedScore(): ?int
    {
        $tokensPerSecond = $this->getTokensPerSecond();
        if ($tokensPerSecond === null) {
            return null;
        }
        $score = ($tokensPerSecond / self::REFERENCE_TOKENS_PER_SECOND_FOR_FULL_SCORE) * 100.0;
        return (int)round(min(100.0, max(0.0, $score)));
    }
}
