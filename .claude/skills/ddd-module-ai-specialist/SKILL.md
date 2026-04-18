---
name: ddd-module-ai-specialist
description: Work with AI models, prompts, cost tracking, and Argus AI integration from the ddd-ai module. Use when adding LLM/image/embedding capabilities to entities, managing prompts, or estimating AI costs.
metadata:
  author: mgamadeus
  version: "1.0.0"
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
)]
```

Response formats: `RESPONSE_FORMAT_DEFAULT` (free text), `RESPONSE_FORMAT_JSON_OBJECT` (forces JSON output)

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

**Vendors:** `VENDOR_OPENAI`, `VENDOR_GOOGLE`, `VENDOR_META`, `VENDOR_BLACK_FOREST_LABS`, `VENDOR_FALAI`

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
