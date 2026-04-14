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
    /** @var string Prompt for Entity translations with Translatable  */
    public const string APP_TRANSLATIONS_ENTITY_TRANSLATABLE = 'Common.Translations.Entity.Translatable';

    /** @var string Prompt for App UI translations (single locale, informal tone) */
    public const string APP_TRANSLATIONS_APP_TRANSLATIONS_SINGLE_LOCALE_INFORMAL = 'Common.Translations.AppTranslations.SingleLocaleInformal';

    /** @var string Prompt for App UI translations (single locale, formal tone) */
    public const string APP_TRANSLATIONS_APP_TRANSLATIONS_SINGLE_LOCALE_FORMAL = 'Common.Translations.AppTranslations.SingleLocaleFormal';

    /** @var string Handles Support cases of type track creation */
    public const string APP_SUPPORT_TRACKS_TRACK_GENERATOR = 'Support.Tracks.TrackGenerator';

    /** @var string Extracts the date of the most recent customer writing in a support conversation */
    public const string APP_SUPPORT_TRACKS_REQUEST_DATE_EXTRACTOR = 'Support.Tracks.RequestDateExtractor';

    /** @var string Generates high-detail MediaItem descriptions based on images */
    public const string APP_COMMON_MEDIAITEMS_IMAGE_DESCRIPTION_GENERATOR = 'Common.MediaItems.ImageDescriptionGenerator';

    /** @var string Detects the primary language code of a text */
    public const string APP_COMMON_TEXTS_DETECTED_LANGUAGE = 'Common.Texts.DetectedLanguage';

    /** @var string Extracts only the new message content from a raw email body (strips reply chains, signatures, disclaimers) */
    public const string APP_SUPPORT_SUPPORT_EMAILS_CLEANED_BODY = 'Support.SupportEmails.CleanedBody';

    /** @var string Extracts the first message content from a raw email body (keeps signature, strips disclaimers) */
    public const string APP_SUPPORT_SUPPORT_EMAILS_CLEANED_BODY_FIRST_MESSAGE = 'Support.SupportEmails.CleanedBodyFirstMessage';

    /** @var string Generates a short title and summary from first customer messages of a support ticket */
    public const string APP_SUPPORT_TICKETS_SUMMARY_AND_TITLE_GENERATOR = 'Support.Tickets.SummaryAndTitleGenerator';

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
