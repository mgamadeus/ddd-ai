<?php

declare (strict_types=1);

namespace DDD\Domain\AI\Entities\Models;

use DDD\Domain\AI\Entities\Models\Settings\AILanguageModelSetting;
use DDD\Domain\AI\Entities\Prompts\AIPrompt;
use DDD\Domain\AI\Services\AIModelsService;
use DDD\Domain\AI\Services\AIPromptsService;
use DDD\Domain\Common\Entities\Money\MoneyAmount;
use DDD\Domain\Base\Entities\ChangeHistory\ChangeHistoryTrait;
use DDD\Domain\Base\Entities\Entity;
use DDD\Domain\Base\Repo\DB\Database\DatabaseIndex;
use DDD\Domain\Common\Entities\MediaItems\GenericMediaItemContent;
use DDD\Domain\Common\Entities\MediaItems\Photo;
use DDD\Infrastructure\Exceptions\BadRequestException;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Infrastructure\Validation\Constraints\Choice;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Cache\InvalidArgumentException;
use ReflectionClassConstant;
use ReflectionException;

/**
 * @method static AIModelsService getService()
 */
class AIModel extends Entity
{
    use ChangeHistoryTrait;

    /** @var string Large Language Models */
    public const string TYPE_LANGUAGE = 'LANGUAGE';

    /** @var string Image Models */
    public const string TYPE_IMAGE = 'IMAGE';

    /** @var string Audio Models */
    public const string TYPE_AUDIO = 'AUDIO';

    /** @var string Embeddings Models */
    public const string TYPE_EMBEDDINGS = 'EMBEDDINGS';

    /** @var string Model Vendor OpenAI */
    public const string VENDOR_OPENAI = 'OPENAI';

    /** @var string Model Vendor Google */
    public const string VENDOR_GOOGLE = 'GOOGLE';

    /** @var string Model Vendor Meta */
    public const string VENDOR_META = 'META';

    /** @var string Model Vendor Black Forest Labs */
    public const string VENDOR_BLACK_FOREST_LABS = 'BLACK_FOREST_LABS';

    /** @var string Model Vendor FALAI */
    public const string VENDOR_FALAI = 'FALAI';

    /**
     * @var string Reasoning effort: none (fastest, minimal thinking)
     * @description Use when you want maximum speed and minimal reasoning.
     */
    public const string REASONING_EFFORT_NONE = 'none';

    /**
     * @var string Reasoning effort: low (fast, light reasoning)
     * @description Use for fast one-shot answers with some light reasoning.
     */
    public const string REASONING_EFFORT_LOW = 'low';

    /**
     * @var string Reasoning effort: medium (balanced)
     * @description Default-like balance between speed and reasoning quality.
     */
    public const string REASONING_EFFORT_MEDIUM = 'medium';

    /**
     * @var string Reasoning effort: high (slower, deeper reasoning)
     * @description Use for complex multi-step tasks requiring deeper reasoning.
     */
    public const string REASONING_EFFORT_HIGH = 'high';

    /**
     * @var string Reasoning effort: xhigh (slowest, maximum reasoning)
     * @description Use for hardest reasoning tasks; highest latency.
     */
    public const string REASONING_EFFORT_XHIGH = 'xhigh';

    /**
     * @var string OpenAI GPT-4o Model
     * @description Advanced multimodal model with vision and text capabilities. Balances performance and cost for diverse tasks.
     * @usage Ideal for tasks requiring image processing (e.g., analyzing charts, OCR) or complex text generation (e.g., summarization, translation). Suitable for most modern applications.
     * @notes Training cutoff: October 2024. Supports vision and basic reasoning. Cost-efficient compared to GPT-4, with 128K context length.
     */
    public const string MODEL_OPENAI_GPT4_O = 'OPENAI.GPT4_O';

    /**
     * @var string OpenAI GPT-4o Mini Model
     * @description Lightweight, cost-efficient multimodal model with vision support. Optimized for high-throughput, simple tasks.
     * @usage Use for cost-sensitive applications like chatbots, quick text processing, or lightweight image analysis. Best for high-volume, low-complexity tasks.
     * @notes Training cutoff: October 2024. 128K context, vision-enabled. Extremely low cost makes it ideal for scaling.
     */
    public const string MODEL_OPENAI_GPT4_O_MINI = 'OPENAI.GPT4_O_MINI';

    /**
     * @var string OpenAI GPT-5 Model
     * @description Flagship multimodal model with advanced reasoning and vision. Designed for complex, high-stakes tasks.
     * @usage Use for demanding tasks like scientific analysis, multi-step reasoning, or large-scale multimodal processing (e.g., video/text analysis). High context length (272K input).
     * @notes Training cutoff: June 2025. Supports advanced reasoning and vision. Higher cost, but powerful for enterprise-grade applications.
     */
    public const string MODEL_OPENAI_GPT5 = 'OPENAI.GPT5';

    /**
     * @var string OpenAI GPT-5 Mini Model
     * @description Optimized version of GPT-5 with similar capabilities but lower cost. Multimodal with strong reasoning.
     * @usage Use for cost-efficient complex tasks requiring reasoning or vision, such as automated code review or detailed image analysis. Good balance of power and cost.
     * @notes Training cutoff: June 2025. 272K context, vision and reasoning enabled. More affordable than GPT-5.
     */
    public const string MODEL_OPENAI_GPT5_MINI = 'OPENAI.GPT5_MINI';

    /**
     * @var string OpenAI GPT-5 Nano Model
     * @description Ultra-lightweight GPT-5 variant for high-throughput, multimodal tasks with minimal cost.
     * @usage Use for high-volume tasks like real-time chat, lightweight vision tasks, or simple reasoning. Ideal for scaling with budget constraints.
     * @notes Training cutoff: June 2025. 272K context, vision and reasoning enabled. Cheapest in GPT-5 series.
     */
    public const string MODEL_OPENAI_GPT5_NANO = 'OPENAI.GPT5_NANO';

    /**
     * @var string OpenAI GPT-4.1 Model
     * @description Long-context multimodal model with improved performance over GPT-4o. Focused on large-scale text and vision tasks.
     * @usage Use for tasks requiring massive context (1M tokens), like document analysis, codebases, or image-based data extraction. Not optimized for reasoning.
     * @notes Training cutoff: October 2024. 1M context, vision-enabled, no advanced reasoning. Cost-efficient for large inputs.
     */
    public const string MODEL_OPENAI_GPT4_1 = 'OPENAI.GPT4_1';

    /**
     * @var string OpenAI GPT-4.1 Mini Model
     * @description Smaller version of GPT-4.1 with long context and vision, optimized for efficiency.
     * @usage Use for large-context tasks (e.g., summarizing long documents, analyzing images) where cost is a concern but high context is needed.
     * @notes Training cutoff: October 2024. 1M context, vision-enabled, no advanced reasoning. More affordable than GPT-4.1.
     */
    public const string MODEL_OPENAI_GPT4_1_MINI = 'OPENAI.GPT4_1_MINI';

    /**
     * @var string OpenAI GPT-4.1 Nano Model
     * @description Ultra-efficient GPT-4.1 variant for large-context, low-cost tasks with vision support.
     * @usage Use for high-throughput tasks with large inputs (e.g., batch processing of documents or images). Ideal for cost-sensitive, large-scale applications.
     * @notes Training cutoff: October 2024. 1M context, vision-enabled, no advanced reasoning. Cheapest in GPT-4.1 series.
     */
    public const string MODEL_OPENAI_GPT4_1_NANO = 'OPENAI.GPT4_1_NANO';

    /**
     * @var string OpenAI o3 Model
     * @description Advanced reasoning model with multimodal capabilities, designed for complex problem-solving.
     * @usage Use for tasks requiring deep reasoning, such as mathematical proofs, logical analysis, or multimodal reasoning with images. High context (200K input).
     * @notes Training cutoff: April 2025. 300K total context, vision and advanced reasoning enabled. High compute cost for premium performance.
     */
    public const string MODEL_OPENAI_O3 = 'OPENAI.O3';

    /**
     * @var string OpenAI o3 Mini Model
     * @description Cost-efficient reasoning model with multimodal support, slightly less powerful than o3.
     * @usage Use for reasoning-heavy tasks (e.g., STEM problems, code debugging) or vision-based reasoning where cost is a factor. High context (200K input).
     * @notes Training cutoff: April 2025. 300K total context, vision and advanced reasoning enabled. More affordable than o3.
     */
    public const string MODEL_OPENAI_O3_MINI = 'OPENAI.O3_MINI';

    /**
     * @var string OpenAI o3 Pro Model
     * @description Premium reasoning model with maximum compute for complex multimodal tasks.
     * @usage Use for mission-critical tasks requiring top-tier reasoning (e.g., advanced research, complex simulations) and vision processing. High context (200K input).
     * @notes Training cutoff: April 2025. 300K total context, vision and advanced reasoning enabled. Highest cost in o-series.
     */
    public const string MODEL_OPENAI_O3_PRO = 'OPENAI.O3_PRO';

    /**
     * @var string OpenAI o4 Mini Model
     * @description Lightweight reasoning model with multimodal support, optimized for speed and cost.
     * @usage Use for fast, reasoning-based tasks with vision (e.g., quick data analysis, image-based queries) in cost-sensitive applications. High context (200K input).
     * @notes Training cutoff: April 2025. 300K total context, vision and advanced reasoning enabled. Cheapest in o-series.
     */
    public const string MODEL_OPENAI_O4_MINI = 'OPENAI.O4_MINI';

    /**
     * @var string OpenAI GPT-OSS-120B Model
     * @description Open-weight 117B MoE model (5.1B active) for high-reasoning tasks. Optimized for single H100 GPU with MXFP4 quantization. Supports function calling and structured outputs.
     * @usage Use for agentic workflows, complex reasoning, or structured data tasks requiring high performance. Ideal for production-grade applications with 131K context.
     * @notes Training cutoff: ~2025. Text-only, Apache 2.0 license. Extremely cost-efficient via OpenRouter.
     */
    public const string MODEL_OPENAI_GPT_OSS_120B = 'OPENAI.GPT_OSS_120B';

    /**
     * @var string OpenAI GPT-OSS-20B Model
     * @description Open-weight 21B MoE model (3.6B active) for low-latency inference. Deployable on consumer hardware, supports reasoning and function calling.
     * @usage Use for cost-efficient reasoning, fine-tuning, or agentic tasks like chatbots or tool use. Suitable for lightweight setups with 131K context.
     * @notes Training cutoff: ~2025. Text-only, Apache 2.0 license. Ultra-low cost via OpenRouter.
     */
    public const string MODEL_OPENAI_GPT_OSS_20B = 'OPENAI.GPT_OSS_20B';

    /**
     * @var string OpenAI GPT-5.2 Model
     * @description Newer GPT-5 family iteration for agentic coding + long context.
     */
    public const string MODEL_OPENAI_GPT5_2 = 'OPENAI.GPT5_2';

    /**
     * @var string OpenAI GPT-5.2 Pro Model
     * @description Highest-accuracy GPT-5.2 variant.
     */
    public const string MODEL_OPENAI_GPT5_2_PRO = 'OPENAI.GPT5_2_PRO';

    /**
     * @var string OpenAI GPT-5.3 Chat Model
     * @description Conversational GPT-5.3 variant for high-throughput chat workloads.
     */
    public const string MODEL_OPENAI_GPT5_3_CHAT = 'OPENAI.GPT5_3_CHAT';

    /**
     * @var string OpenAI GPT-5.4 Model
     * @description Current GPT-5.4 flagship model.
     */
    public const string MODEL_OPENAI_GPT5_4 = 'OPENAI.GPT5_4';

    /**
     * @var string OpenAI GPT-5.4 Pro Model
     * @description Highest-performance GPT-5.4 Pro variant.
     */
    public const string MODEL_OPENAI_GPT5_4_PRO = 'OPENAI.GPT5_4_PRO';

    /**
     * @var string OpenAI GPT-5.4 Mini Model
     * @description Fast, cost-efficient GPT-5.4 variant close to flagship performance. Ideal for coding assistants and subagents.
     * @usage Use for coding assistance, subagent orchestration, or complex tasks where speed and cost matter. 400K context (272K input, 128K output).
     * @notes Training cutoff: August 2025. Reasoning model with vision. $0.75/$4.50 per 1M tokens.
     */
    public const string MODEL_OPENAI_GPT5_4_MINI = 'OPENAI.GPT5_4_MINI';

    /**
     * @var string OpenAI GPT-5.4 Nano Model
     * @description Smallest, cheapest GPT-5.4 variant (API-only). Best for classification, extraction, ranking, and subagents.
     * @usage Use for high-volume classification, data extraction, ranking, or lightweight subagent tasks. 400K context (272K input, 128K output).
     * @notes Training cutoff: August 2025. Reasoning model with vision. $0.20/$1.25 per 1M tokens.
     */
    public const string MODEL_OPENAI_GPT5_4_NANO = 'OPENAI.GPT5_4_NANO';

    /**
     * @var string OpenAI text-embedding-3-small
     * @description Efficient embeddings model for semantic search / RAG.
     */
    public const string MODEL_OPENAI_TEXT_EMBEDDING_3_SMALL = 'OPENAI.TEXT_EMBEDDING_3_SMALL';

    /**
     * @var string OpenAI text-embedding-3-large
     * @description Highest-quality embeddings model for retrieval.
     */
    public const string MODEL_OPENAI_TEXT_EMBEDDING_3_LARGE = 'OPENAI.TEXT_EMBEDDING_3_LARGE';

    /**
     * @var string OpenAI GPT-4o Mini Transcribe
     * @description Cost-efficient speech-to-text model.
     */
    public const string MODEL_OPENAI_GPT4_O_MINI_TRANSCRIBE = 'OPENAI.GPT4_O_MINI_TRANSCRIBE';

    /**
     * @var string OpenAI GPT-4o Transcribe
     * @description Higher-accuracy speech-to-text model.
     */
    public const string MODEL_OPENAI_GPT4_O_TRANSCRIBE = 'OPENAI.GPT4_O_TRANSCRIBE';

    /**
     * @var string OpenAI GPT-4o Mini TTS
     * @description Text-to-speech model.
     */
    public const string MODEL_OPENAI_GPT4_O_MINI_TTS = 'OPENAI.GPT4_O_MINI_TTS';

    /**
     * @var string Meta Llama 3.1 8B Instruct Model
     * @description Fast, efficient 8B model for text-based reasoning and structured outputs. Competitive with closed-source models, optimized for high-throughput tasks.
     * @usage Use for cost-sensitive applications like chatbots, text analysis, or structured data generation. 131K context, ideal for scalable deployments.
     * @notes Training cutoff: ~2024. Text-only, subject to Meta’s Acceptable Use Policy. Extremely low cost via OpenRouter.
     */
    public const string MODEL_META_LLAMA_3_1_8B_INSTRUCT = 'META.LLAMA_3_1_8B_INSTRUCT';

    /** @var string FluxAI 1.1 Pro Model */
    public const string MODEL_FLUXAI_1_1_PRO = 'FLUXAI.1.1_PRO';

    /** @var string FluxAI 1-Schnell Model */
    public const string MODEL_FLUXAI_1_SCHNELL = 'FLUXAI.1_SCHNELL';

    /** @var string FALAI Model SANA SPRINT */
    public const string MODEL_FALAI_SANA_SPRINT = 'FALAI.SANA.SPRINT';

    /** @var string FALAI MODEL FLUX 1 SCHNELL */
    public const string MODEL_FALAI_FLUX_1_SCHNELL = 'FALAI.FLUX_1_SCHNELL';

    /** @var string FALAI MODEL RUNDIFFUSION JUGGERNAUT FLUX LIGHTNING */
    public const string MODEL_FALAI_RUNDIFFUSION_JUGGERNAUT_FLUX_LIGHTNING = 'FALAI.RUNDIFFUSION_JUGGERNAUT_FLUX_LIGHTNING';

    /** @var string FALAI MODEL SANA V1.5 1.6B */
    public const string MODEL_FALAI_SANA_V1_5_1_6B = 'FALAI.SANA_V1_5_1_6B';

    /** @var string FALAI MODEL SANA V1.5 4.8B */
    public const string MODEL_FALAI_SANA_V1_5_4_8B = 'FALAI.SANA_V1_5_4_8B';

    /** @var string FALAI MODEL LUMA PHOTON */
    public const string MODEL_FALAI_LUMA_PHOTON = 'FALAI.LUMA_PHOTON';

    /** @var string FALAI MODEL FLUX 1 DEV */
    public const string MODEL_FALAI_FLUX_1_DEV = 'FALAI.FLUX_1_DEV';

    /** @var string FALAI MODEL IMAGEN3 FAST */
    public const string MODEL_FALAI_IMAGEN3_FAST = 'FALAI.IMAGEN3_FAST';

    /** @var string FALAI MODEL STABLE DIFFUSION V3 MEDIUM */
    public const string MODEL_FALAI_STABLE_DIFFUSION_V3_MEDIUM = 'FALAI.STABLE_DIFFUSION_V3_MEDIUM';

    /** @var string FALAI MODEL FLUX PRO V1.1 ULTRA */
    public const string MODEL_FALAI_FLUX_PRO_V1_1_ULTRA = 'FALAI.FLUX_PRO_V1_1_ULTRA';

    /** @var string FALAI MODEL RECRAFT V3 TEXT TO IMAGE */
    public const string MODEL_FALAI_RECRAFT_V3_TEXT_TO_IMAGE = 'FALAI.RECRAFT_V3_TEXT_TO_IMAGE';

    /** @var string FALAI MODEL IMAGEN4 PREVIEW */
    public const string MODEL_FALAI_IMAGEN4_PREVIEW = 'FALAI.IMAGEN4_PREVIEW';

    /** @var string FALAI MODEL RUNDIFFUSION PHOTO FLUX */
    public const string MODEL_FALAI_RUNDIFFUSION_PHOTO_FLUX = 'FALAI.RUNDIFFUSION_PHOTO_FLUX';

    /** @var string FALAI MODEL FLUX PRO NEW */
    public const string MODEL_FALAI_FLUX_PRO_NEW = 'FALAI.FLUX_PRO_NEW';

    /** @var string FALAI MODEL DREAMO */
    public const string MODEL_FALAI_DREAMO = 'FALAI.DREAMO';

    /** @var string FALAI MODEL HIDREAM I1 FULL */
    public const string MODEL_FALAI_HIDREAM_I1_FULL = 'FALAI.HIDREAM_I1_FULL';

    /** @var string FALAI MODEL RUNDIFFUSION JUGGERNAUT FLUX PRO */
    public const string MODEL_FALAI_RUNDIFFUSION_JUGGERNAUT_FLUX_PRO = 'FALAI.RUNDIFFUSION_JUGGERNAUT_FLUX_PRO';

    /** @var string FALAI MODEL LUMINA IMAGE V2 */
    public const string MODEL_FALAI_LUMINA_IMAGE_V2 = 'FALAI.LUMINA_IMAGE_V2';

    /** @var string FALAI MODEL FLUX PRO KONTEXT MAX TEXT TO IMAGE */
    public const string MODEL_FALAI_FLUX_PRO_KONTEXT_MAX_TEXT_TO_IMAGE = 'FALAI.FLUX_PRO_KONTEXT_MAX_TEXT_TO_IMAGE';

    /** @var string FALAI MODEL FLUX PRO KONTEXT TEXT TO IMAGE */
    public const string MODEL_FALAI_FLUX_PRO_KONTEXT_TEXT_TO_IMAGE = 'FALAI.FLUX_PRO_KONTEXT_TEXT_TO_IMAGE';

    /** @var string OpenAI DALL-E 2 Image Model */
    public const string MODEL_OPENAI_DALL_E_2 = 'OPENAI.DALL_E_2';

    /** @var string OpenAI DALL-E 3 Image Model */
    public const string MODEL_OPENAI_DALL_E_3 = 'OPENAI.DALL_E_3';

    /** @var string OpenAI GPT Image 1 Image Model */
    public const string MODEL_OPENAI_GPT_IMAGE_1 = 'OPENAI.GPT_IMAGE_1';

    /** @var string Google Gemini 2.5 Flash Image Preview Model */
    public const string MODEL_GOOGLE_GEMINI_2_5_FLASH_IMAGE_PREVIEW = 'GOOGLE.GEMINI_2_5_FLASH_IMAGE_PREVIEW';

    /** @var string Google Gemini 2.5 Pro */
    public const string MODEL_GOOGLE_GEMINI_2_5_PRO = 'GOOGLE.GEMINI_2_5_PRO';

    /** @var string Google Gemini 2.5 Flash */
    public const string MODEL_GOOGLE_GEMINI_2_5_FLASH = 'GOOGLE.GEMINI_2_5_FLASH';

    /** @var string Google Gemini 3.0 Pro Preview */
    public const string MODEL_GOOGLE_GEMINI_3_0_PRO_PREVIEW = 'GOOGLE.GEMINI_3_0_PRO_PREVIEW';

    /** @var string The type of the Model */
    #[Choice(callback: [self::class, 'getModelTypes'])]
    public string $type;

    /** @var string The vendor of the Model */
    #[Choice(callback: [self::class, 'getModelVendors'])]
    public string $vendor;

    /** @var string The name of the Model */
    #[DatabaseIndex(indexType: DatabaseIndex::TYPE_UNIQUE)]
    #[Choice(callback: [self::class, 'getModelNames'])]
    public string $name;

    /** @var string Description of the model and its capabilities */
    public ?string $description;

    /** @var string The external id of the model at the AI service provider */
    public string $externalId;

    /** @var string|null The external id of the model on OpenRouter (if available) */
    public ?string $openRouterExternalId = null;

    /** @var bool|null If true, model is a reasoning model, applicable only to Text models */
    public ?bool $isReasoningModel = false;

    /** @var bool|null If true, model accepts multi-modal input and can process images */
    public ?bool $hasVisionCapabilities = false;

    /** @var AILanguageModelSetting The Settings of the Model */
    public AILanguageModelSetting $settings;

    /**
     * Return all model types based on class constants
     * @return array
     * @throws ReflectionException
     */
    public static function getModelTypes()
    {
        $reflectionClass = static::getReflectionClass();
        $constants = $reflectionClass->getConstants(ReflectionClassConstant::IS_PUBLIC);
        $modelConstants = [];

        foreach ($constants as $name => $value) {
            if (strpos($name, 'TYPE_') === 0) {
                $modelConstants[] = $value;
            }
        }

        return $modelConstants;
    }

    /**
     * Return all model vendors based on class constants
     * @return array
     * @throws ReflectionException
     */
    public static function getModelVendors()
    {
        $reflectionClass = static::getReflectionClass();
        $constants = $reflectionClass->getConstants(ReflectionClassConstant::IS_PUBLIC);
        $modelConstants = [];

        foreach ($constants as $name => $value) {
            if (strpos($name, 'VENDOR_') === 0) {
                $modelConstants[] = $value;
            }
        }

        return $modelConstants;
    }

    /**
     * Returns all model names based on class constants
     * @return array
     * @throws ReflectionException
     */
    public static function getModelNames()
    {
        $reflectionClass = static::getReflectionClass();
        $constants = $reflectionClass->getConstants(ReflectionClassConstant::IS_PUBLIC);
        $modelConstants = [];

        foreach ($constants as $name => $value) {
            if (strpos($name, 'MODEL_') === 0) {
                $modelConstants[] = $value;
            }
        }

        return $modelConstants;
    }

    /**
     * @return string
     */
    public function uniqueKey(): string
    {
        $key = $this->id ?? null;
        if (!$key) {
            $key = $this->name;
        }
        return self::uniqueKeyStatic($key);
    }

    /**
     * Calculates and returns estimated costs for given Prompt
     *
     * @param AIPrompt $aiPrompt
     * @param string|array|null $userContent
     * @return MoneyAmount|null
     */
    public function getEstimatedCostsForPrompt(AIPrompt &$aiPrompt, string|array|null $userContent = null): ?MoneyAmount
    {
        $inputTokens = $aiPrompt->getEstimatedInputTokens();
        if ($userContent) {
            $userContentString = '';
            if (is_array($userContent)) {
                foreach ($userContent as $element) {
                    if (isset($element['type']) && $element['type'] == 'text' && isset($element['text'])) {
                        $userContentString .= $element['text'];
                    } elseif (isset($element['type']) && $element['type'] == 'image_url' && isset($element['image_url']['image'])) {
                        /** @var Photo $photo */
                        $photo = $element['image_url']['image'];
                        /** @var GenericMediaItemContent $mediaItemContent */
                        $mediaItemContent = $photo->mediaItemContent;

                        // Calculate costs for image based on OpenAI's guidelines
                        $detail = $element['detail'] ?? 'auto'; // Assume 'low' detail if not specified
                        $inputTokens += $this->calculateImageTokenCost($photo, $detail);
                    }
                }
            } else {
                $userContentString = $userContent;
            }
            $inputTokens += AIPromptsService::getTokenCountForString($userContentString);
        }
        // we assume output tokens will costs estimat
        $outputTokens = $aiPrompt->getEstimatedOuputTokens();
        return $this->getEstimatedCostsForTokens($inputTokens, $outputTokens);
    }


    /**
     * Calculate token count for a given Photo object based on detail level
     *
     * @param Photo $photo The Photo object to calculate cost for
     * @param string $detail The detail level ('low', 'high', or 'auto')
     * @return int The token count for the given photo and detail level
     */
    public function calculateImageTokenCost(Photo $photo, string $detail = 'auto'): int
    {
        /** @var GenericMediaItemContent $mediaItemContent */
        if (!isset($photo->mediaItemContent)) {
            return 0;
        }
        $mediaItemContent = $photo->mediaItemContent;
        $width = $mediaItemContent->width;
        $height = $mediaItemContent->height;
        $tokenCount = 0;

        if ($detail === 'low' || ($detail === 'auto' && max($width, $height) <= 512)) {
            $tokenCount = 65; // Fixed cost for low detail images
        } else {
            // For high detail or auto (if image is larger than 512px in any dimension)
            $numSquares = ceil(max($width, $height) / 512);
            $tokenCount = $numSquares * 129; // 129 tokens per 512px square in high detail
        }

        return (int)$tokenCount;
    }

    /**
     * Calculates and returns esstimated costs for given in and output token count
     * @param int $promptTokens
     * @param int $outputTokens
     * @return MoneyAmount|null
     */
    public function getEstimatedCostsForTokens(int $promptTokens, int $outputTokens): ?MoneyAmount
    {
        if (!($this?->settings->costsPer1000KInputTokens ?? null) || !($this?->settings->costsPer1000KOutputTokens ?? null)) {
            return null;
        }
        $totalCosts = $promptTokens / 1000 * $this->settings->costsPer1000KInputTokens->amount + $outputTokens / 1000 * $this->settings->costsPer1000KOutputTokens->amount;
        return new MoneyAmount($totalCosts, 'USD');
    }

    /**
     * @return string Removes any ```JSON / HTML etc. markup
     */
    public static function removeProgrammingLanguageMarkup(string $input): string
    {
        // Regular expression to match and optionally remove Markdown code blocks with ```html
        $pattern = '/```[a-zA-Z]+\n?(.*?)```/s';

        // Replace the matched pattern with just the content inside the code block, or the entire text if no code block is present
        $cleanedText = preg_replace($pattern, '$1', $input);
        return $cleanedText;
    }
    //public static function estimateCost(int $inputTokens, int $ouputTokens, string $model)
}
