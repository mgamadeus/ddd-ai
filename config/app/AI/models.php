<?php

use DDD\Domain\AI\Entities\Models\AIModel;

return [
    // ── GPT-4o family (still widely used, multimodal) ──────────────────────
    AIModel::MODEL_OPENAI_GPT4_O => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_O_MINI => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4o-mini',
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
        ],
    ],

    // ── GPT-4.1 family (long context, still relevant) ──────────────────────
    AIModel::MODEL_OPENAI_GPT4_1 => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_1_MINI => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1-mini',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT4_1_NANO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-4.1-nano',
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
        ],
    ],

    // ── GPT-5 family – budget/nano tiers ───────────────────────────────────
    AIModel::MODEL_OPENAI_GPT5 => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_MINI => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5-mini',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_NANO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5-nano',
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
        ],
    ],

    // ── GPT-5.2 family (previous frontier, still available) ────────────────
    AIModel::MODEL_OPENAI_GPT5_2 => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.2',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Previous GPT-5.2 frontier model. Superseded by GPT-5.4 – prefer that for new integrations. Still available as a value option vs the newer flagship. 400K context. Training cutoff: June 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 400000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 200000,
            'costsPer1000InputTokensInUSD' => 0.00175,
            'costsPer1000OuputTokensInUSD' => 0.014,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_2_PRO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.2-pro',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Previous GPT-5.2 Pro variant. Superseded by GPT-5.4 Pro for max-precision tasks. Still usable but prefer GPT-5.4 Pro for new integrations.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 400000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 200000,
            'costsPer1000InputTokensInUSD' => 0.021,
            'costsPer1000OuputTokensInUSD' => 0.168,
        ],
    ],

    // ── GPT-5.3 Instant – ChatGPT everyday model, also in API ──────────────
    // Released March 4, 2026. API alias: gpt-5.3-chat-latest
    // Best for: high-volume conversational tasks, fewer refusals, lower hallucinations.
    AIModel::MODEL_OPENAI_GPT5_3_CHAT => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.3-chat-latest',
        'isReasoningModel' => false,
        'hasVisionCapabilities' => true,
        'description' => 'GPT-5.3 Instant: the ChatGPT everyday model, also available in the API. Optimised for conversational quality, fewer refusals, and lower hallucination rates. Not a reasoning model – use for chat, content generation, and high-throughput conversational workloads. Training cutoff: August 2025.',
        'settings' => [
            'maxTokens' => 400000,
            'maxInputTokens' => 400000,
            'maxOutputTokens' => 128000,
            'maxPracticallyUsableInputTokens' => 200000,
            'costsPer1000InputTokensInUSD' => 0.00175,
            'costsPer1000OuputTokensInUSD' => 0.014,
        ],
    ],

    // ── GPT-5.4 family – current flagship (released March 5, 2026) ─────────
    // 1.05M context window, built-in computer use, tool search, compaction.
    // Note: prompts >272K tokens are billed at 2× input + 1.5× output for the full session.
    AIModel::MODEL_OPENAI_GPT5_4 => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4',
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
        ],
    ],
    AIModel::MODEL_OPENAI_GPT5_4_PRO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-5.4-pro',
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
        ],
    ],

    // ── o-series reasoning models ──────────────────────────────────────────
    AIModel::MODEL_OPENAI_O3 => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o3',
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
        ],
    ],
    AIModel::MODEL_OPENAI_O3_MINI => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o3-mini',
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
        ],
    ],
    AIModel::MODEL_OPENAI_O3_PRO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o3-pro',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Premium reasoning model for mission-critical tasks with vision. Use for advanced research or complex simulations. 200K input context. Training cutoff: April 2025.',
        'settings' => [
            'maxTokens' => 300000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 100000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.02,
            'costsPer1000OuputTokensInUSD' => 0.08,
        ],
    ],
    AIModel::MODEL_OPENAI_O4_MINI => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'o4-mini',
        'isReasoningModel' => true,
        'hasVisionCapabilities' => true,
        'description' => 'Lightweight reasoning model with vision for fast, cost-effective tasks. Use for quick data analysis or image-based queries. 200K input context. Training cutoff: April 2025.',
        'settings' => [
            'maxTokens' => 300000,
            'maxInputTokens' => 200000,
            'maxOutputTokens' => 100000,
            'maxPracticallyUsableInputTokens' => 100000,
            'costsPer1000InputTokensInUSD' => 0.0006,
            'costsPer1000OuputTokensInUSD' => 0.0024,
        ],
    ],

    // ── Open-source / open-weight models ──────────────────────────────────
    AIModel::MODEL_OPENAI_GPT_OSS_120B => [
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
        'settings' => [
            'costsPerImageInUSD' => 0.02,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH_IMAGE_PREVIEW => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash-image-preview',
        'settings' => [
            'costsPerImageInUSD' => 0.039,
        ],
    ],
    AIModel::MODEL_OPENAI_DALL_E_3 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'dall-e-3',
        'settings' => [
            'costsPerImageInUSD' => 0.08,
        ],
    ],
    AIModel::MODEL_OPENAI_GPT_IMAGE_1 => [
        'type' => AIModel::TYPE_IMAGE,
        'vendor' => AIModel::VENDOR_OPENAI,
        'externalId' => 'gpt-image-1',
        'settings' => [
            'costsPerImageInUSD' => 0.063,
        ],
    ],

    // ── Google Gemini ──────────────────────────────────────────────────────
    AIModel::MODEL_GOOGLE_GEMINI_2_5_PRO => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-pro',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.00125,
            'costsPer1000OuputTokensInUSD' => 0.01,
            'inputTierThresholdTokens' => 200000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.0025,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.015,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_2_5_FLASH => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-2.5-flash',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.0003,
            'costsPer1000OuputTokensInUSD' => 0.0025,
        ],
    ],
    AIModel::MODEL_GOOGLE_GEMINI_3_0_PRO_PREVIEW => [
        'type' => AIModel::TYPE_LANGUAGE,
        'vendor' => AIModel::VENDOR_GOOGLE,
        'externalId' => 'gemini-3-pro-preview',
        'settings' => [
            'maxTokens' => 1048576,
            'maxInputTokens' => 1048576,
            'maxOutputTokens' => 65536,
            'maxPracticallyUsableInputTokens' => 65536,
            'costsPer1000InputTokensInUSD' => 0.002,
            'costsPer1000OuputTokensInUSD' => 0.012,
            'inputTierThresholdTokens' => 200000,
            'costsPer1000InputTokensInUSDAboveThreshold' => 0.004,
            'costsPer1000OutputTokensInUSDAboveThreshold' => 0.018,
        ],
    ],
];