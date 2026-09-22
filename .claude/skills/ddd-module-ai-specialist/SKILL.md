---
name: ddd-module-ai-specialist
description: "Work with AI models, prompts, cost tracking and Argus AI integration from the ddd-ai module — the model catalog in config/app/AI/models.php (60+ rows with vendor, externalId/openRouterExternalId, context and price settings, speed and benchmark measurements, agentEligible/agentTier), AIModelsService lookup and scope-based selection, AIPrompt markdown templates with parameter substitution and project overrides, cost estimation per prompt/tokens/image, image generation, embeddings, the batch endpoints, and the trait's Gemini-vs-OpenAI payload auto-detection. Includes decision models (TypeSafe System One), which answer typed questions instead of text and are never agent-eligible. Use when adding LLM, image or embedding capabilities to entities, adding or repricing a model in the catalog, choosing a model for a scope, managing prompts, or estimating AI costs."
metadata:
  author: mgamadeus
  version: "1.1.0"
  module: mgamadeus/ddd-ai
---

# AI Module Specialist

AI model management, prompt system, and Argus AI integration.

> **Base patterns:** See core skills in `vendor/mgamadeus/ddd`. For Argus repos, see `vendor/mgamadeus/ddd-argus`.

## When to Use

- Adding LLM-powered features to Argus entities
- Working with AI prompts and parameter substitution
- Estimating token costs for AI operations
- Adding image generation capabilities
- Creating text embeddings

## Adding AI to an Argus Entity

Combine `#[ArgusLoad]` + `#[ArgusLanguageModel]` + `ArgusAILanguageModelTrait`:

```php
use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Entities\Prompts\AIPrompt;
use DDD\Domain\AI\Repo\Argus\Attributes\ArgusLanguageModel;
use DDD\Domain\AI\Repo\Argus\Traits\ArgusAILanguageModelTrait;
use DDD\Domain\Base\Repo\Argus\Attributes\ArgusLoad;
use DDD\Domain\Base\Repo\Argus\Traits\ArgusTrait;
use DDD\Domain\Base\Repo\Argus\Utils\ArgusCache;

#[ArgusLoad(
    loadEndpoint: 'POST:/ai/openRouter/chatCompletions',
    cacheLevel: ArgusCache::CACHELEVEL_MEMORY_AND_DB,
    cacheTtl: ArgusCache::CACHELEVEL_NONE          // Don't cache AI responses by default
)]
#[ArgusLanguageModel(
    defaultAIModelName: AIModel::MODEL_OPENAI_GPT5_4_MINI,
    defaultAIPromptName: 'MyApp.Analysis.Classify',
    responseFormat: ArgusLanguageModel::RESPONSE_FORMAT_JSON_OBJECT,
)]
class ArgusMyAnalysis extends MyAnalysis
{
    use ArgusTrait, ArgusAILanguageModelTrait;

    // REQUIRED: Provide the user content to send to the LLM
    public function getUserContent(): string|array
    {
        return $this->textToAnalyze;
        // For multimodal (text + images):
        // return [
        //     ['type' => 'text', 'text' => 'Describe this image'],
        //     ['type' => 'image_url', 'image_url' => ['image' => $photoEntity]],
        // ];
    }

    // REQUIRED: Set prompt parameters
    public function getAIPromptWithParametersApplied(): AIPrompt
    {
        $prompt = $this->getAIPrompt();
        $prompt->setParameter('locale', 'de-DE');
        $prompt->setParameter('categories', 'food, drink, dessert');
        return $prompt;
    }

    // REQUIRED: Store the AI response
    protected function applyLoadResult(string $resultText): void
    {
        $parsed = json_decode($resultText);
        $this->category = $parsed->category ?? null;
        $this->confidence = $parsed->confidence ?? 0.0;
    }
}
```

### `#[ArgusLanguageModel]` Attribute

```php
#[ArgusLanguageModel(
    defaultAIModelName: AIModel::MODEL_OPENAI_GPT5_4,   // Model constant
    defaultAIPromptName: 'MyApp.Analysis.Classify',       // Prompt dot-path
    defaultAIPromptVersion: null,                          // Optional version
    temperature: 0.8,                                       // 0.0-1.0+ (skipped for reasoning models)
    responseFormat: ArgusLanguageModel::RESPONSE_FORMAT_JSON_OBJECT, // or DEFAULT
    systemPromptName: 'MyApp.System.Instructions',          // Optional system prompt
    preferredProviders: ['groq', 'cerebras'],               // OpenRouter-only: ordered provider preference
)]
```

Response formats: `RESPONSE_FORMAT_DEFAULT` (free text), `RESPONSE_FORMAT_JSON_OBJECT` (forces JSON output)

**`preferredProviders`** (OpenRouter only): Ordered list of provider slugs to prefer (e.g. `['groq', 'cerebras']`). Translated into the OpenRouter `provider.order` body parameter with `allow_fallbacks: true`. Has no effect when the load endpoint targets OpenAI directly.

## AIModel (60+ Models)

```php
$modelsService = AIModelsService::instance();
$model = $modelsService->getAIModelByName(AIModel::MODEL_OPENAI_GPT5_4);

$model->type;                   // 'LANGUAGE'
$model->vendor;                 // 'OPENAI'
$model->externalId;             // 'gpt-5.4'
$model->isReasoningModel;       // true
$model->hasVisionCapabilities;  // true
$model->settings->maxInputTokens;  // 1050000
```

**Key model constants:**
- `MODEL_OPENAI_GPT5_4` -- Current flagship, 1M context
- `MODEL_OPENAI_GPT5_4_MINI` -- Fast, 400K context
- `MODEL_OPENAI_GPT5_4_NANO` -- Cheapest
- `MODEL_OPENAI_O3` -- Advanced reasoning
- `MODEL_GOOGLE_GEMINI_2_5_PRO` -- 1M context, tiered pricing
- `MODEL_OPENAI_TEXT_EMBEDDING_3_SMALL` -- Embeddings

**Vendors:** `VENDOR_OPENAI`, `VENDOR_GOOGLE`, `VENDOR_ANTHROPIC`, `VENDOR_XAI`, `VENDOR_META`, `VENDOR_PERPLEXITY`, `VENDOR_BLACK_FOREST_LABS`, `VENDOR_FALAI`, and the Chinese group served through Western OpenRouter providers: `VENDOR_MINIMAX`, `VENDOR_MOONSHOT`, `VENDOR_ALIBABA`, `VENDOR_ZAI` — plus `VENDOR_TYPESAFE` (decision models, below). `AIModel::getModelVendors()` reflects over the `VENDOR_` constants, so a new vendor constant is all the `#[Choice]` validation needs.

### Decision models — a catalog row that is not a chat model

`MODEL_TYPESAFE_JEV_1_13` (TypeSafe "System One") answers TYPED questions — choice (≤ 255 options), score (2–10
levels), noul (yes/no) — with calibrated probabilities and a confidence, in one parallel pass, and produces **no
text**. It is reachable only through OpenRouter's `POST /api/v1/systemone` route, so a consuming app overrides the
payload and response protocol methods of the language-model trait in its own Argus repo; the module only carries the
catalog row.

Such a row is `TYPE_LANGUAGE` (the settings hydrate as `AILanguageModelSetting`, and that price row is what
`getEstimatedCostsForTokens()` reads), `agentEligible => false`, and carries **no** `agentTier`, `speed` or
`benchmarks`: no agentic benchmark constant applies to a decision model, and a null `agenticScore` is never consulted
while `agentEligible` is false. Going through the catalog is what makes the call a normal AI op — scope and envelope
enforced, counted against the account's cap, logged per call, and priced from this row.

**Types:** `TYPE_LANGUAGE`, `TYPE_IMAGE`, `TYPE_AUDIO`, `TYPE_EMBEDDINGS`

## AIPrompt (Template System)

Prompts are markdown files at `config/app/AI/Prompts/{dot.path.as.directories}.md`:

```php
$promptsService = AIPromptsService::instance();
$prompt = $promptsService->getAIPromptByName('Common.Texts.DetectedLanguage');
// Loads: config/app/AI/Prompts/Common/Texts/DetectedLanguage.md

$prompt->setParameter('locale', 'de-DE');
$prompt->setParameter('text', $inputText);

$rendered = $prompt->getPromtTextWithParametersApplied();  // {%locale%} replaced
$tokens = $prompt->getEstimatedInputTokens();              // Token count
```

**Override:** Place file at same path in your project's `config/app/AI/Prompts/`.

## Cost Estimation

```php
$model = $modelsService->getAIModelByName(AIModel::MODEL_OPENAI_GPT5_4);

// From prompt + content
$costs = $model->getEstimatedCostsForPrompt($prompt, $userContent);  // MoneyAmount

// From token counts
$costs = $model->getEstimatedCostsForTokens(promptTokens: 1000, outputTokens: 500);

// Image token calculation (for vision models)
$imageTokens = $model->calculateImageTokenCost($photo, 'auto');  // 65 low, 129/512px high
```

Tiered pricing supported (Gemini: different rates above `inputTierThresholdTokens`).

Budget enforcement: override `isAIBudgetSufficientForOperation()` in your Argus entity. Throws `AiBudgetExceededException` (extends `ForbiddenException`).

## Image Generation

Use `ArgusAIImageModelTrait` (extends language trait):

```php
#[ArgusLoad(loadEndpoint: 'POST:/ai/falai/imageGeneration')]
#[ArgusLanguageModel(defaultAIModelName: AIModel::MODEL_FALAI_FLUX_PRO_V1_1_ULTRA)]
class ArgusImageGenerator extends MyImage
{
    use ArgusTrait, ArgusAIImageModelTrait;

    protected int $width = 1024;
    protected int $height = 768;
    protected int $numberOfImages = 1;

    public function getUserContent(): string { return $this->prompt; }
    public function getAIPromptWithParametersApplied(): AIPrompt { return $this->getAIPrompt(); }
    protected function applyLoadResult(string $resultText): void { $this->imageUrls = $resultText; }
}
```

## Batch Endpoints

| Endpoint | Service | Purpose |
|----------|---------|---------|
| `POST /ai/openAi/chatCompletions` | `OpenAIService` | Direct OpenAI chat |
| `POST /ai/openAi/embeddings` | `OpenAIService` | Direct OpenAI embeddings |
| `POST /ai/openRouter/chatCompletions` | `OpenRouterService` | Multi-vendor chat |
| `POST /ai/openRouter/embeddings` | `OpenRouterService` | Multi-vendor embeddings |

## Vendor Auto-Detection

The trait auto-detects Google Gemini vs OpenAI/OpenRouter based on `AIModel.vendor`:
- **Gemini:** Builds "parts" array format, `inline_data` for images, `response_mime_type` for JSON
- **OpenAI/OpenRouter:** Chat message format, `response_format` for JSON, `max_completion_tokens` for reasoning models
- Temperature is skipped for reasoning models (o3, o4, GPT-5.4 Pro)
