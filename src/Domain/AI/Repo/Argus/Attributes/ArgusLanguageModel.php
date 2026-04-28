<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Repo\Argus\Attributes;

use DDD\Domain\AI\Entities\Prompts\AIPrompt;
use Attribute;
use DDD\Domain\Base\Entities\Attributes\BaseAttributeTrait;
use DDD\Infrastructure\Traits\Serializer\SerializerTrait;
use DDD\Infrastructure\Validation\Constraints\Choice;

/**
 * encapsules all relevant properties for argus loading
 * used by Trait ArgusLoad
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ArgusLanguageModel
{
    use SerializerTrait, BaseAttributeTrait;

    /** @var string Response format JSON Object */
    public const string RESPONSE_FORMAT_JSON_OBJECT = 'JSON_OBJECT';

    /** @var string Response format Default */
    public const string RESPONSE_FORMAT_DEFAULT = 'DEFAULT';

    /** @var string|null The default AIModel name to use */
    public ?string $defaultAIModelName = '';

    /** @var string|null The default AIPrompt name to use */
    public ?string $defaultAIPromptName = '';

    /** @var int|null The default AIPrompt version to use */
    public ?int $defaultAIPromptVersion = null;

    /** @var float|null The temperature value to be applied */
    public ?float $temperature = null;

    /** @var string|null The desired response Format */
    #[Choice([
            self::RESPONSE_FORMAT_JSON_OBJECT,
            self::RESPONSE_FORMAT_DEFAULT,
        ]
    )]
    public ?string $responseFormat = null;

    /** @var string|null The system prompt name to use for AI operations */
    public ?string $systemPromptName = null;

    /** @var int|null The system prompt version to use */
    public ?int $systemPromptVersion = null;

    /**
     * @var string[]|null Ordered list of OpenRouter provider slugs to prefer (e.g. ['groq', 'cerebras']).
     * Only applied when the load endpoint targets OpenRouter. Translated into the OpenRouter
     * `provider.order` body parameter with `allow_fallbacks: true`.
     */
    public ?array $preferredProviders = null;

    /**
     * @param string|null $defaultAIModelName
     * @param string|null $defaultAIPromptName
     * @param int|null $defaultAIPromptVersion
     * @param float|null $temperature
     * @param string|null $responseFormat
     * @param string|null $systemPromptName
     * @param int|null $systemPromptVersion
     * @param string[]|null $preferredProviders
     */
    public function __construct(
        ?string $defaultAIModelName = null,
        ?string $defaultAIPromptName = null,
        ?int $defaultAIPromptVersion = null,
        ?float $temperature = null,
        ?string $responseFormat = self::RESPONSE_FORMAT_DEFAULT,
        ?string $systemPromptName = null,
        ?int $systemPromptVersion = null,
        ?array $preferredProviders = null,
    ) {
        $this->defaultAIModelName = $defaultAIModelName;
        $this->defaultAIPromptName = $defaultAIPromptName;
        $this->defaultAIPromptVersion = $defaultAIPromptVersion;
        $this->temperature = $temperature;
        $this->responseFormat = $responseFormat;
        $this->systemPromptName = $systemPromptName;
        $this->systemPromptVersion = $systemPromptVersion;
        $this->preferredProviders = $preferredProviders;
    }
}
