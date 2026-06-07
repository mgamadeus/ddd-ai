<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Benchmarks;

use DDD\Domain\Base\Entities\ObjectSet;

/**
 * Typed set of {@see AIModelBenchmark} results for an AIModel — the agentic-capability evidence stored parallel to
 * the model's cost/limit Settings. Mirrors {@see \DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariants}.
 *
 * Curated benchmark set (most→least representative of an in-process, multi-step, function-calling agent loop) with
 * the weights used to blend the present scores into one 0–100 agentic-intelligence score:
 *
 *   BFCL (function/tool-call accuracy, multi-turn)   0.40  — closest proxy: tool choice + argument validity
 *   τ-bench (multi-turn tool-agent, pass^k)          0.30  — multi-step follow-through + reliability
 *   GAIA (general assistant with tools)              0.20  — tool-use reasoning, broad
 *   SWE-bench Verified (coding agent)                0.05  — agentic anchor, low domain overlap
 *   AgentBench (multi-environment agent)             0.05  — breadth
 *
 * The blend is a pure function of the present, weighted scores (renormalised), so a model rated on only a subset
 * still yields a comparable score. Distinct from raw chat quality and from cost — it isolates the agentic axis.
 *
 * @method AIModelBenchmark first()
 * @method AIModelBenchmark getByUniqueKey(string $uniqueKey)
 * @method AIModelBenchmark[] getElements()
 * @property AIModelBenchmark[] $elements
 */
class AIModelBenchmarks extends ObjectSet
{
    public const string BENCHMARK_BFCL = 'BFCL';

    public const string BENCHMARK_TAU_BENCH = 'TAU_BENCH';

    public const string BENCHMARK_GAIA = 'GAIA';

    public const string BENCHMARK_SWE_BENCH_VERIFIED = 'SWE_BENCH_VERIFIED';

    public const string BENCHMARK_AGENT_BENCH = 'AGENT_BENCH';

    /** @var array<string, float> Blend weights by benchmark id (relevance to the in-process agentic tool-use loop). */
    public const array AGENTIC_WEIGHTS = [
        self::BENCHMARK_BFCL => 0.40,
        self::BENCHMARK_TAU_BENCH => 0.30,
        self::BENCHMARK_GAIA => 0.20,
        self::BENCHMARK_SWE_BENCH_VERIFIED => 0.05,
        self::BENCHMARK_AGENT_BENCH => 0.05,
    ];

    /**
     * Weighted blend of the present benchmark scores into a single 0–100 agentic-intelligence score, renormalised
     * over whichever weighted benchmarks are actually present. Benchmarks not in {@see AGENTIC_WEIGHTS} are ignored.
     * Returns null when no weighted benchmark score is present (i.e. the model is effectively unrated).
     */
    public function getAgenticIntelligenceScore(): ?int
    {
        $weightedSum = 0.0;
        $weightTotal = 0.0;
        foreach ($this->getElements() as $benchmark) {
            $weight = self::AGENTIC_WEIGHTS[$benchmark->benchmark] ?? null;
            if ($weight === null) {
                continue;
            }
            $weightedSum += $benchmark->score * $weight;
            $weightTotal += $weight;
        }
        if ($weightTotal <= 0.0) {
            return null;
        }
        return (int)round($weightedSum / $weightTotal);
    }
}
