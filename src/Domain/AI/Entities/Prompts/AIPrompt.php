<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Prompts;

use DDD\Domain\AI\Services\AIPromptsService;
use DDD\Domain\Base\Entities\Entity;

/**
 * @method static AIPromptsService getService()
 */
class AIPrompt extends Entity
{
    /** @var string The name of the AIPrompt */
    public string $name;

    /** @var string The text of the prompt */
    public string $promtText;

    protected array $parameters = [];

    /** @var string|null Used for storing final prompt text in order to avoid multiple executions of parameter replacements */
    protected ?string $promtTextWithParametersApplied = null;

    /** @var int|null Used for storing final estimated input token count in order to avoid multiple executions of parameter replacements */
    protected ?int $estimatedInputTokens = null;

    /**
     * Sets a parameter to adjust variables in prompt, e.g. language => de
     * @param string $parameterName
     * @param string|int|float|bool $parameterValue
     * @return AIPrompt
     */
    public function setParameter(string $parameterName, string|int|float|bool $parameterValue): AIPrompt
    {
        $this->parameters[$parameterName] = $parameterValue;
        // we reset precalculated value
        $this->promtTextWithParametersApplied = null;
        $this->estimatedInputTokens = null;
        return $this;
    }

    public function getPromtTextWithParametersApplied(): string
    {
        if ($this->promtTextWithParametersApplied) {
            return $this->promtTextWithParametersApplied;
        }
        $promtText = $this->promtText;
        foreach ($this->parameters as $parameter => $value) {
            $promtText = str_replace('{%' . $parameter . '%}', (string)$value, $promtText);
        }
        $this->promtTextWithParametersApplied = $promtText;
        return $promtText;
    }

    /**
     * @return int Returns the amount of tokens required for prompt with applied parameters
     */
    public function getEstimatedInputTokens(): int
    {
        if ($this->estimatedInputTokens) {
            return $this->estimatedInputTokens;
        }
        $content = $this->getPromtTextWithParametersApplied();
        $this->estimatedInputTokens = AIPromptsService::getTokenCountForString($content);
        return $this->estimatedInputTokens;
    }

    /**
     * @return int Returns estimation of output tokens, by default we return the same as prompt tokens
     */
    public function getEstimatedOuputTokens(): int
    {
        return $this->getEstimatedInputTokens();
    }

    public function uniqueKey(): string
    {
        return self::uniqueKeyStatic($this->name);
    }
}
