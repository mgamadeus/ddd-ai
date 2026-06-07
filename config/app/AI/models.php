<?php

use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Entities\Models\Benchmarks\AIModelBenchmarks;
use DDD\Domain\AI\Entities\Models\Speed\AIModelSpeedMeasurements;

return [
    // ── GPT-4o family (still widely used, multimodal) ──────────────────────
    AIModel::MODEL_OPENAI_GPT4_O => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 37.5, 'timeToFirstTokenMs' => 816, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-4o&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 119.0, 'timeToFirstTokenMs' => 830, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-4o', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 32.0, 'sourceUrl' => 'https://www.microsoft.com/en-us/research/articles/magentic-one-a-generalist-multi-agent-system-for-solving-complex-tasks/', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 25.1, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 33.2, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o',
        'openRouterExternalId' => 'openai/gpt-4o',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'Multimodal GPT-4o with vision and text. Good for image processing (OCR, charts) and complex text tasks. 128K context, cost-efficient. Training cutoff: October 2024.',
        'settings' => [
            'maxTokens' => 8192,
            'maxInputTokens' => 128000,
            'maxOutputTokens' => 4096,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.0025,
            'costsPer1000OuputTokensInUSD' => 0.01,
            'costsPer1000CachedInputTokensInUSD' => 0.00125, // 50% off input rate
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_O_MINI => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 32.0, 'timeToFirstTokenMs' => 620, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-4o-mini&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 60.0, 'timeToFirstTokenMs' => 1700, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-4o-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 22.5, 'sourceUrl' => 'https://github.com/sierra-research/tau-bench', 'asOf' => '2024-10'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o-mini',
        'openRouterExternalId' => 'openai/gpt-4o-mini',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'Cost-efficient GPT-4o-mini with vision. Best for high-volume simple tasks like chatbots or lightweight image analysis. 128K context. Training cutoff: October 2024.',
        'settings' => [
            'maxTokens' => 4096,
            'maxInputTokens' => 128000,
            'maxOutputTokens' => 16384,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.00015,
            'costsPer1000OuputTokensInUSD' => 0.0006,
            'costsPer1000CachedInputTokensInUSD' => 0.000075, // 50% off input rate
        ],
    ],

    // ── GPT-4.1 family (long context, still relevant) ──────────────────────
    AIModel::MODEL_OPENAI_GPT4_1 => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 53.0, 'timeToFirstTokenMs' => 802, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-4.1-2025-04-14&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 127.7, 'timeToFirstTokenMs' => 930, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-4-1', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 53.96, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 47.1, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 50.3, 'sourceUrl' => 'https://hal.cs.princeton.edu/gaia', 'asOf' => '2026-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 54.6, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1',
        'openRouterExternalId' => 'openai/gpt-4.1',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'Long-context GPT-4.1 with vision for large-scale text and image tasks. Use for document analysis or codebases. 1M context. Training cutoff: October 2024.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 32768,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.002,
            'costsPer1000OuputTokensInUSD' => 0.008,
            'costsPer1000CachedInputTokensInUSD' => 0.0005, // 75% off (cached = 25% of input)
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_1_MINI => [
        // agent-DISQUALIFIED: FLAKY — chains on some paths but dead-ends 2/2 on the ranking 2-hop (live test 2026-06-05). [Loop-entangled — re-evaluate after fix.]
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 41.0, 'timeToFirstTokenMs' => 629, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-4.1-mini-2025-04-14&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 85.1, 'timeToFirstTokenMs' => 710, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-4-1-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 50.45, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 52.9, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 23.6, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1-mini',
        'openRouterExternalId' => 'openai/gpt-4.1-mini',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'Efficient GPT-4.1 with vision for large-context tasks. Use for cost-sensitive document or image analysis. 1M context. Training cutoff: October 2024.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 32768,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0004,
            'costsPer1000OuputTokensInUSD' => 0.0016,
            'costsPer1000CachedInputTokensInUSD' => 0.0001, // 75% off
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_1_NANO => [
        // agent-DISQUALIFIED: never completes a multi-step tool chain — dead-ends after load_skills on every 2-hop task (live test 2026-06-05). [Entangled with the loop empty-turn handling; re-evaluate if that is fixed.]
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 52.5, 'timeToFirstTokenMs' => 998, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-4.1-nano-2025-04-14&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 111.6, 'timeToFirstTokenMs' => 620, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-4-1-nano', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 23.94, 'sourceUrl' => 'https://openlm.ai/swe-bench/', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 33.05, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 17.3, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1-nano',
        'openRouterExternalId' => 'openai/gpt-4.1-nano',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'Ultra-efficient GPT-4.1 for large-context, low-cost tasks with vision. Use for batch processing of documents or images. 1M context. Training cutoff: October 2024.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 32768,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0001,
            'costsPer1000OuputTokensInUSD' => 0.0004,
            'costsPer1000CachedInputTokensInUSD' => 0.000025, // 75% off
        ],
    ],

    // ── GPT-5 family – budget/nano tiers ───────────────────────────────────
    AIModel::MODEL_OPENAI_GPT5 => [
        'agentTier' => AIModel::AGENT_TIER_STANDARD,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 56.5, 'timeToFirstTokenMs' => 3375, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5-2025-08-07&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 91.9, 'timeToFirstTokenMs' => 95210, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 86.5, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 62.8, 'sourceUrl' => 'https://hal.cs.princeton.edu/gaia', 'asOf' => '2026-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 74.9, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5',
        // Agentic: base GPT-5 over-reasons at default effort → slow (~57s) + reasoning-only empty-turn stalls. Low.
        'agenticUseCase' => ['reasoningEffort' => 'low'],
        'openRouterExternalId' => 'openai/gpt-5',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Original GPT-5 flagship with reasoning and vision. Superseded by GPT-5.4 for most tasks; keep for price-sensitive use cases. 400K context. Training cutoff: June 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.01,
            'costsPer1000CachedInputTokensInUSD' => 0.000125, // 90% off (cached = 10% of input)
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_MINI => [
        // agent-DISQUALIFIED: FLAKY — non-deterministic: dead-ended once, succeeded once via start_thread (live test 2026-06-05). [Loop-entangled — re-evaluate after fix.]
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 68.0, 'timeToFirstTokenMs' => 4364, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5-mini-2025-08-07&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 96.5, 'timeToFirstTokenMs' => 118350, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 59.8, 'sourceUrl' => 'https://openlm.ai/swe-bench/', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 55.46, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5-mini',
        'openRouterExternalId' => 'openai/gpt-5-mini',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Cost-efficient GPT-5 variant with reasoning and vision. Use for complex tasks like code review or image analysis with budget constraints. 272K context. Training cutoff: June 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.00025,
            'costsPer1000OuputTokensInUSD' => 0.002,
            'costsPer1000CachedInputTokensInUSD' => 0.000025, // 90% off
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_NANO => [
        // agent-DISQUALIFIED: FLAKY — intermittently dead-ends after load_skills on multi-step (1/2 live test 2026-06-05). No fragile models in the agentic pipeline. [Loop empty-turn-entangled — re-evaluate after that fix.]
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 84.5, 'timeToFirstTokenMs' => 2805, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5-nano-2025-08-07&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 149.7, 'timeToFirstTokenMs' => 103810, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-nano', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 34.8, 'sourceUrl' => 'https://openlm.ai/swe-bench/', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 51.45, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5-nano',
        'openRouterExternalId' => 'openai/gpt-5-nano',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Ultra-light GPT-5 for high-throughput tasks with reasoning and vision. Ideal for real-time chat or simple image tasks. 272K context. Training cutoff: June 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.00005,
            'costsPer1000OuputTokensInUSD' => 0.0004,
            'costsPer1000CachedInputTokensInUSD' => 0.000005, // 90% off
        ],
    ],

    // ── GPT-5.2 family (previous frontier, still available) ────────────────

    // ── GPT-5.3 Instant – ChatGPT everyday model, also in API ──────────────
    // Released March 4, 2026. API alias: gpt-5.3-chat-latest
    // Best for: high-volume conversational tasks, fewer refusals, lower hallucinations.

    // ── GPT-5.4 family – current flagship (released March 5, 2026) ─────────
    // 1.05M context window, built-in computer use, tool search, compaction.
    // Note: prompts >272K tokens are billed at 2× input + 1.5× output for the full session.
    AIModel::MODEL_OPENAI_GPT5_4 => [
        'agentTier' => AIModel::AGENT_TIER_PREMIUM,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 47.0, 'timeToFirstTokenMs' => 2470, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.4-20260305&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 80.8, 'timeToFirstTokenMs' => 248690, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-4', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 87.1, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 78.2, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4',
        // Agentic: low–medium; high → tangential tool-calling/stalls. Low for fast, zielgerichtete tool-calls.
        'agenticUseCase' => ['reasoningEffort' => 'low'],
        'openRouterExternalId' => 'openai/gpt-5.4',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Current GPT-5.4 flagship for complex professional work. Best-in-class coding (absorbs GPT-5.3-Codex), agentic workflows, document/spreadsheet/presentation tasks. Native computer-use, tool search, compaction. 1.05M context window. Training cutoff: August 2025.',
        'settings' => [
            'maxTokens' => 1050000,
            'maxInputTokens' => 1050000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 272000,
            'costsPer1000InputTokensInUSD' => 0.0025,
            'costsPer1000OuputTokensInUSD' => 0.015,
            'costsPer1000CachedInputTokensInUSD' => 0.00025, // 90% off
            // Above-threshold (>272K input): 2x input + 1.5x output for the full session
            'inputTierThresholdTokens' => 272000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.005,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.0225,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_4_PRO => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 8.0, 'timeToFirstTokenMs' => 104107, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.4-pro-20260305&variant=standard', 'asOf' => '2026-06-05'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4-pro',
        'openRouterExternalId' => 'openai/gpt-5.4-pro',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'GPT-5.4 Pro: maximum performance variant for mission-critical tasks. Uses more compute per request (reasoning.effort: medium/high/xhigh only). Responses API only. Slower – use background mode to avoid timeouts. 1.05M context. Training cutoff: August 2025.',
        'settings' => [
            'maxTokens' => 1050000,
            'maxInputTokens' => 1050000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 272000,
            'costsPer1000InputTokensInUSD' => 0.03,
            'costsPer1000OuputTokensInUSD' => 0.18,
            // Above-threshold (>272K input): 2x input + 1.5x output for the full session
            'inputTierThresholdTokens' => 272000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.06,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.27,
        ],
    ],

    AIModel::MODEL_OPENAI_GPT5_4_MINI => [
        'agentTier' => AIModel::AGENT_TIER_STANDARD,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 53.0, 'timeToFirstTokenMs' => 706, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.4-mini-20260317&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 158.8, 'timeToFirstTokenMs' => 16290, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-4-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 83.3, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4-mini',
        // Agentic: low fixes the verified load_skills→reasoning-only empty-turn stall (eval 2026-06).
        'agenticUseCase' => ['reasoningEffort' => 'low'],
        'openRouterExternalId' => 'openai/gpt-5.4-mini',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Fast, cost-efficient GPT-5.4 variant close to flagship performance. Ideal for coding assistants, subagent orchestration, or complex tasks where speed and cost matter. 400K context (272K input, 128K output). Training cutoff: August 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.00075,
            'costsPer1000OuputTokensInUSD' => 0.0045,
            'costsPer1000CachedInputTokensInUSD' => 0.000075, // 90% off
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_4_NANO => [
        // agent-DISQUALIFIED: FLAKY — type-invalid first tool call + degenerate single-day ranking window (live test 2026-06-05). [Loop-entangled — re-evaluate after fix.]
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 48.0, 'timeToFirstTokenMs' => 832, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.4-nano-20260317&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 152.7, 'timeToFirstTokenMs' => 5100, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-4-nano', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 76.0, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4-nano',
        'openRouterExternalId' => 'openai/gpt-5.4-nano',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Smallest, cheapest GPT-5.4 variant (API-only). Best for high-volume classification, data extraction, ranking, and lightweight subagent tasks. 400K context (272K input, 128K output). Training cutoff: August 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.0002,
            'costsPer1000OuputTokensInUSD' => 0.00125,
            'costsPer1000CachedInputTokensInUSD' => 0.00002, // 90% off
        ],
    ],

    // ── o-series reasoning models ──────────────────────────────────────────
    AIModel::MODEL_OPENAI_O3 => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 82.0, 'timeToFirstTokenMs' => 3151, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/o3-2025-04-16&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 144.5, 'timeToFirstTokenMs' => 6840, 'sourceUrl' => 'https://artificialanalysis.ai/models/o3', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 63.05, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 80.7, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 32.73, 'sourceUrl' => 'https://hal.cs.princeton.edu/gaia', 'asOf' => '2026-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 69.1, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o3',
        'openRouterExternalId' => 'openai/o3',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Advanced reasoning model with vision. Use for complex problem-solving, math, or multimodal reasoning. 200K input context. Training cutoff: April 2025.',
        'settings' => [
            'maxTokens' => 300000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 100000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.002,
            'costsPer1000OuputTokensInUSD' => 0.008,
            'costsPer1000CachedInputTokensInUSD' => 0.0005, // 75% off
        ],
    ],
    AIModel::MODEL_OPENAI_O3_MINI => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 179.0, 'timeToFirstTokenMs' => 7571, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/o3-mini-2025-01-31&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 214.9, 'timeToFirstTokenMs' => 6190, 'sourceUrl' => 'https://artificialanalysis.ai/models/o3-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 28.7, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 49.3, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o3-mini',
        'openRouterExternalId' => 'openai/o3-mini',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Cost-efficient reasoning model with vision. Use for STEM tasks or vision-based reasoning on a budget. 200K input context. Training cutoff: April 2025.',
        'settings' => [
            'maxTokens' => 300000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 100000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.0011,
            'costsPer1000OuputTokensInUSD' => 0.0044,
            'costsPer1000CachedInputTokensInUSD' => 0.00055, // 50% off
        ],
    ],
    AIModel::MODEL_OPENAI_O4_MINI => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 79.0, 'timeToFirstTokenMs' => 2282, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/o4-mini-2025-04-16&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 163.2, 'timeToFirstTokenMs' => 30730, 'sourceUrl' => 'https://artificialanalysis.ai/models/o4-mini', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 57.3, 'sourceUrl' => 'https://arxiv.org/pdf/2506.07982', 'asOf' => '2025-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 53.24, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 58.18, 'sourceUrl' => 'https://hal.cs.princeton.edu/gaia', 'asOf' => '2026-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 68.1, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o4-mini',
        'openRouterExternalId' => 'openai/o4-mini',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Lightweight reasoning model with vision for fast, cost-effective tasks. Use for quick data analysis or image-based queries. 200K input context. Training cutoff: April 2025.',
        'settings' => [
            'maxTokens' => 300000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 100000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.0011,
            'costsPer1000OuputTokensInUSD' => 0.0044,
            'costsPer1000CachedInputTokensInUSD' => 0.000275, // 75% off (cached = 25% of input)
        ],
    ],

    // ── Open-source / open-weight models ──────────────────────────────────
    AIModel::MODEL_OPENAI_GPT_OSS_120B => [
        // harmony channel markers (e.g. `<|channel|>commentary`) leak into tool names via the OpenAI-chat egress →
        // native tool-calling fails in the agent loop. Excluded from agentic (ModelManager) selection.
        'supportsNativeToolCalling' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 139.0, 'timeToFirstTokenMs' => 422, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-oss-120b&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 335.5, 'timeToFirstTokenMs' => 850, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-oss-120b', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 65.8, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 62.4, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-oss-120b',
        'openRouterExternalId' => 'openai/gpt-oss-120b',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => false,
        'description' => 'Open-weight 117B MoE model (5.1B active) for high-reasoning tasks. Use for agentic workflows, function calling, or structured outputs on single H100 GPU. 131K context, Apache 2.0 license. Training cutoff: ~2025.',
        'settings' => [
            'maxTokens' => 131072,
            'maxInputTokens' => 131072,
            'maxOutputTokens' => 16384,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.00009,
            'costsPer1000OuputTokensInUSD' => 0.00045,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT_OSS_20B => [
        // harmony channel markers (e.g. `<|channel|>commentary`) leak into tool names via the OpenAI-chat egress →
        // native tool-calling fails in the agent loop. Excluded from agentic (ModelManager) selection.
        'supportsNativeToolCalling' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 62.0, 'timeToFirstTokenMs' => 608, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-oss-20b&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 239.8, 'timeToFirstTokenMs' => 770, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-oss-20b', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 60.7, 'sourceUrl' => 'https://arxiv.org/html/2508.10925v1', 'asOf' => '2026-06'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 60.2, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-oss-20b',
        'openRouterExternalId' => 'openai/gpt-oss-20b',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => false,
        'description' => 'Open-weight 21B MoE model (3.6B active) for low-latency tasks. Use for cost-efficient reasoning, function calling, or fine-tuning on consumer hardware. 131K context, Apache 2.0 license. Training cutoff: ~2025.',
        'settings' => [
            'maxTokens' => 131072,
            'maxInputTokens' => 131072,
            'maxOutputTokens' => 16384,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.00004,
            'costsPer1000OuputTokensInUSD' => 0.00016,
        ],
    ],

    // ── Meta Llama ─────────────────────────────────────────────────────────
    AIModel::MODEL_META_LLAMA_3_1_8B_INSTRUCT => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 25.5, 'timeToFirstTokenMs' => 349, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=meta-llama/llama-3.1-8b-instruct&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 152.8, 'timeToFirstTokenMs' => 900, 'sourceUrl' => 'https://artificialanalysis.ai/models/llama-3-1-instruct-8b', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 25.83, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_META,
        'externalId' => 'llama-3.1-8b-instruct',
        'openRouterExternalId' => 'meta-llama/llama-3.1-8b-instruct',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => false,
        'description' => 'Fast, efficient 8B Llama 3.1 model for text-based tasks. Use for high-throughput reasoning or structured outputs. 131K context, Meta AUP applies. Training cutoff: ~2024.',
        'settings' => [
            'maxTokens' => 131072,
            'maxInputTokens' => 131072,
            'maxOutputTokens' => 16384,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.000015,
            'costsPer1000OuputTokensInUSD' => 0.00002,
        ],
    ],

    // ── Embedding models ───────────────────────────────────────────────────
    AIModel::MODEL_OPENAI_TEXT_EMBEDDING_3_SMALL => [
        'type' => AIModel::TYPE_EMBEDDINGS,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'text-embedding-3-small',
        'openRouterExternalId' => 'openai/text-embedding-3-small',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Efficient embedding model for semantic search/RAG.',
        'settings' => [
            'maxTokens' => 8192,
            'maxInputTokens' => 8192,
            'maxOutputTokens' => 0,
            'maxPracticallyUsableInputTokens' => 8192,
            'costsPer1000InputTokensInUSD' => 0.00002,
            'costsPer1000OuputTokensInUSD' => 0.0,
        ],
    ],
    AIModel::MODEL_OPENAI_TEXT_EMBEDDING_3_LARGE => [
        'type' => AIModel::TYPE_EMBEDDINGS,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'text-embedding-3-large',
        'openRouterExternalId' => 'openai/text-embedding-3-large',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Highest-quality embedding model for best retrieval accuracy.',
        'settings' => [
            'maxTokens' => 8192,
            'maxInputTokens' => 8192,
            'maxOutputTokens' => 0,
            'maxPracticallyUsableInputTokens' => 8192,
            'costsPer1000InputTokensInUSD' => 0.00013,
            'costsPer1000OuputTokensInUSD' => 0.0,
        ],
    ],

    // ── Audio models ───────────────────────────────────────────────────────
    AIModel::MODEL_OPENAI_GPT4_O_MINI_TRANSCRIBE => [
        'type' => AIModel::TYPE_AUDIO,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o-mini-transcribe',
        'openRouterExternalId' => null,
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Speech-to-text model (better WER than Whisper).',
        'settings' => [
            'costsPerMinuteInUSD' => 0.003,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_O_TRANSCRIBE => [
        'type' => AIModel::TYPE_AUDIO,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o-transcribe',
        'openRouterExternalId' => null,
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Higher-accuracy speech-to-text model.',
        'settings' => [
            'costsPerMinuteInUSD' => 0.006,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_O_MINI_TTS => [
        'type' => AIModel::TYPE_AUDIO,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o-mini-tts',
        'openRouterExternalId' => null,
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Fast text-to-speech model.',
        'settings' => [
            'costsPerMinuteInUSD' => 0.015,
        ],
    ],

    // ── Image generation models ────────────────────────────────────────────
    AIModel::MODEL_FLUXAI_1_1_PRO => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_BLACK_FOREST_LABS,
        'externalId' => 'black-forest-labs/FLUX.1.1-pro',
        'settings' => [
            'costsPerImageInUSD' => 0.05,
        ],
    ],
    AIModel::MODEL_FLUXAI_1_SCHNELL => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_BLACK_FOREST_LABS,
        'externalId' => 'black-forest-labs/FLUX.1-schnell',
        'settings' => [
            'costsPerImageInUSD' => 0.0027,
        ],
    ],
    AIModel::MODEL_FALAI_SANA_SPRINT => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/sana/sprint',
        'settings' => [
            'costsPerImageInUSD' => 0.0025,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_1_SCHNELL => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-1/schnell',
        'settings' => [
            'costsPerImageInUSD' => 0.003,
        ],
    ],
    AIModel::MODEL_FALAI_RUNDIFFUSION_JUGGERNAUT_FLUX_LIGHTNING => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'rundiffusion-fal/juggernaut-flux/lightning',
        'settings' => [
            'costsPerImageInUSD' => 0.006,
        ],
    ],
    AIModel::MODEL_FALAI_SANA_V1_5_1_6B => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/sana/v1.5/1.6b',
        'settings' => [
            'costsPerImageInUSD' => 0.0075,
        ],
    ],
    AIModel::MODEL_FALAI_SANA_V1_5_4_8B => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/sana/v1.5/4.8b',
        'settings' => [
            'costsPerImageInUSD' => 0.01,
        ],
    ],
    AIModel::MODEL_FALAI_LUMA_PHOTON => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/luma-photon',
        'settings' => [
            'costsPerImageInUSD' => 0.019,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_1_DEV => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-1/dev',
        'settings' => [
            'costsPerImageInUSD' => 0.025,
        ],
    ],
    AIModel::MODEL_FALAI_IMAGEN3_FAST => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/imagen3/fast',
        'settings' => [
            'costsPerImageInUSD' => 0.025,
        ],
    ],
    AIModel::MODEL_FALAI_STABLE_DIFFUSION_V3_MEDIUM => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/stable-diffusion-v3-medium',
        'settings' => [
            'costsPerImageInUSD' => 0.035,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_PRO_V1_1_ULTRA => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-pro/v1.1-ultra',
        'settings' => [
            'costsPerImageInUSD' => 0.04,
        ],
    ],
    AIModel::MODEL_FALAI_RECRAFT_V3_TEXT_TO_IMAGE => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/recraft/v3/text-to-image',
        'settings' => [
            'costsPerImageInUSD' => 0.04,
        ],
    ],
    AIModel::MODEL_FALAI_IMAGEN4_PREVIEW => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/imagen4/preview',
        'settings' => [
            'costsPerImageInUSD' => 0.04,
        ],
    ],
    AIModel::MODEL_FALAI_RUNDIFFUSION_PHOTO_FLUX => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'rundiffusion-fal/rundiffusion-photo-flux',
        'settings' => [
            'costsPerImageInUSD' => 0.045,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_PRO_NEW => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-pro/new',
        'settings' => [
            'costsPerImageInUSD' => 0.05,
        ],
    ],
    AIModel::MODEL_FALAI_DREAMO => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/dreamo',
        'settings' => [
            'costsPerImageInUSD' => 0.05,
        ],
    ],
    AIModel::MODEL_FALAI_HIDREAM_I1_FULL => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/hidream-i1-full',
        'settings' => [
            'costsPerImageInUSD' => 0.05,
        ],
    ],
    AIModel::MODEL_FALAI_RUNDIFFUSION_JUGGERNAUT_FLUX_PRO => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'rundiffusion-fal/juggernaut-flux/pro',
        'settings' => [
            'costsPerImageInUSD' => 0.055,
        ],
    ],
    AIModel::MODEL_FALAI_LUMINA_IMAGE_V2 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/lumina-image/v2',
        'settings' => [
            'costsPerImageInUSD' => 0.075,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_PRO_KONTEXT_MAX_TEXT_TO_IMAGE => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-pro/kontext/max/text-to-image',
        'settings' => [
            'costsPerImageInUSD' => 0.08,
        ],
    ],
    AIModel::MODEL_FALAI_FLUX_PRO_KONTEXT_TEXT_TO_IMAGE => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_FALAI,
        'externalId' => 'fal-ai/flux-pro/kontext/text-to-image',
        'settings' => [
            'costsPerImageInUSD' => 0.08,
        ],
    ],
    AIModel::MODEL_OPENAI_DALL_E_2 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'dall-e-2',
        'openRouterExternalId' => null,
        'settings' => [
            'costsPerImageInUSD' => 0.02,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH_IMAGE_PREVIEW => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash-image-preview',
        'openRouterExternalId' => 'google/gemini-2.5-flash-image-preview',
        'settings' => [
            'costsPerImageInUSD' => 0.039,
        ],
    ],
    AIModel::MODEL_OPENAI_DALL_E_3 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'dall-e-3',
        'openRouterExternalId' => null,
        'settings' => [
            'costsPerImageInUSD' => 0.08,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT_IMAGE_1 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-image-1',
        'openRouterExternalId' => null,
        'settings' => [
            'costsPerImageInUSD' => 0.063,
        ],
    ],

    // ── Google Gemini ──────────────────────────────────────────────────────
    AIModel::MODEL_GOOGLE_GEMINI_2_5_PRO => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 88.0, 'timeToFirstTokenMs' => 2634, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-2.5-pro&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 142.2, 'timeToFirstTokenMs' => 22680, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-2-5-pro', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 33.3, 'sourceUrl' => 'https://pricepertoken.com/leaderboards/benchmark/gaia', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 54.1, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 63.8, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-pro',
        'openRouterExternalId' => 'google/gemini-2.5-pro',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Google Gemini 2.5 Pro — reasoning + vision flagship. 1M context, tiered pricing >200K. Training cutoff: January 2025.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.01,
            'costsPer1000CachedInputTokensInUSD' => 0.000125, // 90% off
            'inputTierThresholdTokens' => 200000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.0025,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.015,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH => [
        'agentTier' => AIModel::AGENT_TIER_CHEAP,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 58.0, 'timeToFirstTokenMs' => 728, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-2.5-flash&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 206.3, 'timeToFirstTokenMs' => 650, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-2-5-flash', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 56.24, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 14.9, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 54.0, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash',
        // Agentic: low thinking budget for fast, zielgerichtete tool-calls (hybrid reasoning model).
        'agenticUseCase' => ['reasoningEffort' => 'low'],
        'openRouterExternalId' => 'google/gemini-2.5-flash',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Google Gemini 2.5 Flash — hybrid reasoning with configurable thinkingBudget. 1M context, no tier. Multimodal in, text out. Training cutoff: January 2025.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0003,
            'costsPer1000OuputTokensInUSD' => 0.0025,
            'costsPer1000CachedInputTokensInUSD' => 0.00003, // 90% off (text/img/vid; audio cached = 0.0001)
        ],
    ],
    // NOTE: Google API slug is `gemini-3-pro-preview` (no `.0`).
    // Status: DISCONTINUED as of 2026-03-26. Google redirects to gemini-3.1-pro-preview.

    // ── Legacy OpenAI GPT-4 (kept for backwards compatibility, no caching) ──

    // ── GPT-5 Chat Completions variant (official id: gpt-5-chat-latest)

    // ── GPT-5 Search API (Chat Completions wrapper with native web_search tool)
    AIModel::MODEL_OPENAI_GPT5_SEARCH_API => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5-search-api',
        'openRouterExternalId' => null,
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'GPT-5 with built-in web search. Chat-Completions wrapper around GPT-5 + web_search tool. Returns answers grounded in real-time web results with citations.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 272000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 136000,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.01,
            'costsPer1000CachedInputTokensInUSD' => 0.000125, // 90% off
            'costsPerWebSearchCallInUSD' => 0.01, // $10/1K (reasoning-model tier)
        ],
    ],

    // ── Gemini 2.5 Flash Image (stable, GA) ────────────────────────────────
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH_IMAGE => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash-image',
        'openRouterExternalId' => 'google/gemini-2.5-flash-image',
        'description' => 'Gemini 2.5 Flash Image (stable). Image generation + edit. ~1290 output tokens per 1024px image @ $30/1M = $0.039/image. Batch rate 50% off.',
        'settings' => [
            'costsPerImageInUSD' => 0.039, // 1024px default; per-token actually $30/1M output × 1290 tokens
        ],
    ],

    // ── Gemini 3 Flash Preview (API slug: gemini-3-flash-preview, no `.0`) ─
    AIModel::MODEL_GOOGLE_GEMINI_3_0_FLASH_PREVIEW => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 67.5, 'timeToFirstTokenMs' => 1201, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-3-flash-preview-20251217&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 179.9, 'timeToFirstTokenMs' => 7590, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-3-flash-reasoning', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 43.3, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 78.0, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3-flash-preview',
        'openRouterExternalId' => 'google/gemini-3-flash-preview',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Google Gemini 3 Flash Preview (thinking model). 1M context. Released 2025-12-17.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0005,
            'costsPer1000OuputTokensInUSD' => 0.003,
        ],
    ],

    // ── Gemini 3.1 Pro Preview (current flagship of 3.1 series) ────────────
    AIModel::MODEL_GOOGLE_GEMINI_3_1_PRO_PREVIEW => [
        'agentTier' => AIModel::AGENT_TIER_PREMIUM,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 75.5, 'timeToFirstTokenMs' => 5253, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-3.1-pro-preview-20260219&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 139.7, 'timeToFirstTokenMs' => 41270, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-3-1-pro-preview', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 95.6, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 78.8, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3.1-pro-preview',
        // Agentic: thinking_level medium (default high). temperature stays null → provider default 1.0 (Gemini 3.x mandates it).
        'agenticUseCase' => ['reasoningEffort' => 'medium'],
        'openRouterExternalId' => 'google/gemini-3.1-pro-preview',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Google Gemini 3.1 Pro Preview — current flagship with medium-thinking reasoning level. 1M context, tiered pricing >200K. Released 2026-02-19.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.002,
            'costsPer1000OuputTokensInUSD' => 0.012,
            'costsPer1000CachedInputTokensInUSD' => 0.0002, // 90% off
            'inputTierThresholdTokens' => 200000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.004,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.018,
        ],
    ],

    // ── Gemini 3.1 Flash Image Preview ("Nano Banana 2") ───────────────────
    // Image cost varies by resolution: 512px=$0.045, 1024px=$0.067, 2048px=$0.101, 4096px=$0.151
    // Stored as 1024px default. Multi-resolution callers must compute per-resolution.
    AIModel::MODEL_GOOGLE_GEMINI_3_1_FLASH_IMAGE_PREVIEW => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3.1-flash-image-preview',
        'openRouterExternalId' => 'google/gemini-3.1-flash-image-preview',
        'description' => 'Gemini 3.1 Flash Image Preview ("Nano Banana 2"). Image gen + edit + multi-turn. Token-priced at $60/1M output. Default 1024px ≈ $0.067/image.',
        'settings' => [
            'costsPerImageInUSD' => 0.067, // 1024px default
        ],
    ],

    // ── Gemini 3.1 Flash Lite (API slug: gemini-3.1-flash-lite-preview) ────
    AIModel::MODEL_GOOGLE_GEMINI_3_1_FLASH_LITE => [
        // agent-DISQUALIFIED: DANGEROUS: invents a ranking ('Position 1') from an empty `[0]:` getRankings payload (live re-test 2026-06-05) — fabrication, like gemini-2.5-flash-lite. Not loop-fixable.
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 59.0, 'timeToFirstTokenMs' => 817, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-3.1-flash-lite-preview-20260303&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 335.5, 'timeToFirstTokenMs' => 6120, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-3-1-flash-lite-preview', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_TAU_BENCH, 'score' => 31.3, 'sourceUrl' => 'https://benchlm.ai/benchmarks/tau2Bench', 'asOf' => '2026-06-02'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3.1-flash-lite-preview',
        'openRouterExternalId' => 'google/gemini-3.1-flash-lite-preview',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Google Gemini 3.1 Flash Lite Preview — extended thinking / CoT. Multimodal in, text out. 1M context. Released 2026-03-03.',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.00025, // text/img/vid; audio: 0.0005
            'costsPer1000OuputTokensInUSD' => 0.0015,
            'costsPer1000CachedInputTokensInUSD' => 0.000025, // 90% off (text); audio cached: 0.00005
        ],
    ],

    // ── Perplexity Sonar (search-grounded model with variant pricing) ──────
    AIModel::MODEL_PERPLEXITY_SONAR => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_PERPLEXITY,
        'externalId' => 'sonar',
        'openRouterExternalId' => 'perplexity/sonar',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => false,
        'description' => 'Perplexity Sonar — web-grounded search model with native citations. 128K context. Search context tier pricing per request.',
        'settings' => [
            'maxTokens' => 128000,
            'maxInputTokens' => 127000,
            'maxOutputTokens' => 4096,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.001,
            'costsPer1000OuputTokensInUSD' => 0.001,
            'costsPerRequestInUSDByVariant' => [
                'search_context_size.low' => 0.005,
                'search_context_size.medium' => 0.008,
                'search_context_size.high' => 0.012,
            ],
        ],
    ],

    // ===== Added 2026-06: Anthropic + xAI vendors, newest OpenAI/Google =====
    AIModel::MODEL_ANTHROPIC_CLAUDE_OPUS_4_8 => [
        'agentTier' => AIModel::AGENT_TIER_PREMIUM,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 40.0, 'timeToFirstTokenMs' => 2228, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=anthropic/claude-4.8-opus-20260528&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 59.8, 'timeToFirstTokenMs' => 14890, 'sourceUrl' => 'https://artificialanalysis.ai/models/claude-opus-4-8', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 88.6, 'sourceUrl' => 'https://www.vellum.ai/blog/claude-opus-4-8-benchmarks-explained', 'asOf' => '2026-06', 'official' => false],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_ANTHROPIC,
        'externalId' => 'claude-opus-4-8',
        // Agentic: high effort is Anthropic's default for agent loops/multi-turn (interleaved adaptive thinking).
        'agenticUseCase' => ['reasoningEffort' => 'high'],
        'openRouterExternalId' => 'anthropic/claude-opus-4.8',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Anthropic flagship: complex reasoning + long-horizon agentic coding. 1M context, 128k output, adaptive thinking. Training cutoff Jan 2026.',
        'settings' => [
            'maxTokens' => 1000000,
            'maxInputTokens' => 1000000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 500000,
            'costsPer1000InputTokensInUSD' => 0.005,
            'costsPer1000OuputTokensInUSD' => 0.025,
            'costsPer1000CachedInputTokensInUSD' => 0.0005,
        ],
    ],
    AIModel::MODEL_ANTHROPIC_CLAUDE_SONNET_4_6 => [
        'agentTier' => AIModel::AGENT_TIER_PREMIUM,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 37.5, 'timeToFirstTokenMs' => 1691, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=anthropic/claude-4.6-sonnet-20260217&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 43.9, 'timeToFirstTokenMs' => 1630, 'sourceUrl' => 'https://artificialanalysis.ai/models/claude-sonnet-4-6', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 76.3, 'sourceUrl' => 'https://www.anthropic.com/news/claude-sonnet-4-6', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_ANTHROPIC,
        'externalId' => 'claude-sonnet-4-6',
        // Agentic: medium is Anthropic's recommended default for Sonnet 4.6 tool-heavy/agentic workflows.
        'agenticUseCase' => ['reasoningEffort' => 'medium'],
        'openRouterExternalId' => 'anthropic/claude-sonnet-4.6',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Best speed/intelligence balance. 1M context, 64k output, extended+adaptive thinking. Training cutoff Jan 2026.',
        'settings' => [
            'maxTokens' => 1000000,
            'maxInputTokens' => 1000000,
            'maxOutputTokens' => 64000,
            'maxPracticallyUsableInputTokens' => 500000,
            'costsPer1000InputTokensInUSD' => 0.003,
            'costsPer1000OuputTokensInUSD' => 0.015,
            'costsPer1000CachedInputTokensInUSD' => 0.0003,
        ],
    ],
    AIModel::MODEL_ANTHROPIC_CLAUDE_HAIKU_4_5 => [
        'agentTier' => AIModel::AGENT_TIER_STANDARD,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 72.5, 'timeToFirstTokenMs' => 779, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=anthropic/claude-4.5-haiku-20251001&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 91.5, 'timeToFirstTokenMs' => 850, 'sourceUrl' => 'https://artificialanalysis.ai/models/claude-4-5-haiku', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 68.7, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_GAIA, 'score' => 56.36, 'sourceUrl' => 'https://hal.cs.princeton.edu/gaia', 'asOf' => '2026-06', 'official' => false],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 73.3, 'sourceUrl' => 'https://www.anthropic.com/news/claude-haiku-4-5', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_ANTHROPIC,
        'externalId' => 'claude-haiku-4-5-20251001',
        // Agentic: low — Haiku 4.5 has no effort param (manual budget); via the proxy, low effort enables a small
        // thinking budget (thinking is off by default but beneficial for tool use). Keeps it fast.
        'agenticUseCase' => ['reasoningEffort' => 'low'],
        'openRouterExternalId' => 'anthropic/claude-haiku-4.5',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Fastest model with near-frontier intelligence. 200k context, 64k output, extended thinking. Training cutoff Jul 2025.',
        'settings' => [
            'maxTokens' => 200000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 64000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.001,
            'costsPer1000OuputTokensInUSD' => 0.005,
            'costsPer1000CachedInputTokensInUSD' => 0.0001,
        ],
    ],
    AIModel::MODEL_XAI_GROK_4_3 => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 117.0, 'timeToFirstTokenMs' => 731, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=x-ai/grok-4.3-20260430&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 186.9, 'timeToFirstTokenMs' => 14700, 'sourceUrl' => 'https://artificialanalysis.ai/models/grok-4-3', 'asOf' => '2026-06-05'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_XAI,
        'externalId' => 'grok-4.3',
        'openRouterExternalId' => 'x-ai/grok-4.3',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'xAI current flagship (2026-04): reasoning, text+image, agentic. 1M context (>200k billed higher). Output cap/cutoff unpublished.',
        'settings' => [
            'maxTokens' => 1000000,
            'maxInputTokens' => 1000000,
            'maxPracticallyUsableInputTokens' => 500000,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.0025,
        ],
    ],
    AIModel::MODEL_XAI_GROK_4_20 => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 102.0, 'timeToFirstTokenMs' => 758, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=x-ai/grok-4.20-20260309&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 185.2, 'timeToFirstTokenMs' => 14760, 'sourceUrl' => 'https://artificialanalysis.ai/models/grok-4-20', 'asOf' => '2026-06-05'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_XAI,
        'externalId' => 'grok-4.20',
        'openRouterExternalId' => 'x-ai/grok-4.20',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'xAI reasoning model (2026-03), fast agentic tool-calling, toggleable reasoning. Text+image, 2M context. Output cap/cutoff unpublished.',
        'settings' => [
            'maxTokens' => 2000000,
            'maxInputTokens' => 2000000,
            'maxPracticallyUsableInputTokens' => 1000000,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.0025,
        ],
    ],
    AIModel::MODEL_XAI_GROK_4_1_FAST => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_XAI,
        'externalId' => 'grok-4.1-fast',
        'openRouterExternalId' => 'x-ai/grok-4.1-fast',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'xAI cheap fast/agentic tier (2025-11), strong tool-calling. Text+image, 2M context. Output cap/cutoff unpublished.',
        'settings' => [
            'maxTokens' => 2000000,
            'maxInputTokens' => 2000000,
            'maxPracticallyUsableInputTokens' => 1000000,
            'costsPer1000InputTokensInUSD' => 0.0002,
            'costsPer1000OuputTokensInUSD' => 0.0005,
            'costsPer1000CachedInputTokensInUSD' => 5e-05,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_5 => [
        'agentTier' => AIModel::AGENT_TIER_PREMIUM,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 41.0, 'timeToFirstTokenMs' => 4256, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.5-20260423&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 63.9, 'timeToFirstTokenMs' => 93320, 'sourceUrl' => 'https://artificialanalysis.ai/models/gpt-5-5', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 82.6, 'sourceUrl' => 'https://www.vals.ai/benchmarks/swebench', 'asOf' => '2026-06', 'official' => false],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.5',
        // Agentic: medium is the vendor-default balance; precise tool selection on large surfaces.
        'agenticUseCase' => ['reasoningEffort' => 'medium'],
        'openRouterExternalId' => 'openai/gpt-5.5',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'OpenAI frontier (2026-04, gpt-5.5-2026-04-23): coding/research/tool-use, reasoning_effort, text+image. 1.05M context, cutoff Dec 2025. >272k input billed 2x in / 1.5x out.',
        'settings' => [
            'maxTokens' => 1178000,
            'maxInputTokens' => 1050000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 525000,
            'costsPer1000InputTokensInUSD' => 0.005,
            'costsPer1000OuputTokensInUSD' => 0.03,
            'costsPer1000CachedInputTokensInUSD' => 0.0005,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_5_PRO => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 22.0, 'timeToFirstTokenMs' => 6244, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=openai/gpt-5.5-pro-20260423&variant=standard', 'asOf' => '2026-06-05'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.5-pro',
        'openRouterExternalId' => 'openai/gpt-5.5-pro',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'OpenAI high-capability Pro reasoning (2026-04) for high-stakes workloads. Text+image, ~1M context. Cached price unpublished.',
        'settings' => [
            'maxTokens' => 1050000,
            'maxInputTokens' => 922000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 461000,
            'costsPer1000InputTokensInUSD' => 0.03,
            'costsPer1000OuputTokensInUSD' => 0.18,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_3_5_FLASH => [
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 117.5, 'timeToFirstTokenMs' => 1876, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-3.5-flash-20260519&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 187.0, 'timeToFirstTokenMs' => 35030, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-3-5-flash', 'asOf' => '2026-06-05'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3.5-flash',
        'openRouterExternalId' => 'google/gemini-3.5-flash',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Gemini 3.5 Flash (GA): most intelligent Flash tier for sustained agentic/coding, configurable thinking. Multimodal, ~1M context, cutoff Jan 2025.',
        'settings' => [
            'maxTokens' => 1114112,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65000,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0015,
            'costsPer1000OuputTokensInUSD' => 0.009,
            'costsPer1000CachedInputTokensInUSD' => 0.00015,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH_LITE => [
        // agent-DISQUALIFIED: DANGEROUS: fabricates tool names / fakes write success / invents rankings (live agentic-fit test 2026-06-05) — a model-honesty failure, not loop-fixable.
        'agentEligible' => false,
        'speed' => [
            ['source' => AIModelSpeedMeasurements::SOURCE_OPENROUTER, 'tokensPerSecond' => 76.0, 'timeToFirstTokenMs' => 576, 'sourceUrl' => 'https://openrouter.ai/api/frontend/stats/endpoint?permaslug=google/gemini-2.5-flash-lite&variant=standard', 'asOf' => '2026-06-05'],
            ['source' => AIModelSpeedMeasurements::SOURCE_ARTIFICIAL_ANALYSIS, 'tokensPerSecond' => 245.3, 'timeToFirstTokenMs' => 450, 'sourceUrl' => 'https://artificialanalysis.ai/models/gemini-2-5-flash-lite', 'asOf' => '2026-06-05'],
        ],
        'benchmarks' => [
            ['benchmark' => AIModelBenchmarks::BENCHMARK_BFCL, 'score' => 36.87, 'sourceUrl' => 'https://gorilla.cs.berkeley.edu/leaderboard.html', 'asOf' => '2026-04-12'],
            ['benchmark' => AIModelBenchmarks::BENCHMARK_SWE_BENCH_VERIFIED, 'score' => 27.6, 'sourceUrl' => 'https://storage.googleapis.com/deepmind-media/Model-Cards/Gemini-2-5-Flash-Lite-Model-Card.pdf', 'asOf' => '2026-06'],
        ],
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash-lite',
        'openRouterExternalId' => 'google/gemini-2.5-flash-lite',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Gemini 2.5 Flash-Lite (GA): fastest/cheapest 2.5 tier, thinking off by default. Multimodal, ~1M context. (Text/image/video price tier.)',
        'settings' => [
            'maxTokens' => 1114112,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65535,
            'maxPracticallyUsableInputTokens' => 524288,
            'costsPer1000InputTokensInUSD' => 0.0001,
            'costsPer1000OuputTokensInUSD' => 0.0004,
            'costsPer1000CachedInputTokensInUSD' => 1e-05,
        ],
    ],
];