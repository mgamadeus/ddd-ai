<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Services;

use DDD\Domain\AI\Entities\Prompts\AIPrompt;
use DDD\Infrastructure\Exceptions\NotFoundException;
use DDD\Infrastructure\Libs\Config;
use DDD\Infrastructure\Services\DDDService;
use DDD\Infrastructure\Services\Service;
use Gioni06\Gpt3Tokenizer\Gpt3Tokenizer;
use Gioni06\Gpt3Tokenizer\Gpt3TokenizerConfig;

class AIPromptsService extends Service
{
    /** @var string Config path prefix for prompts directory */
    public const string CONFIG_PROMPTS_PREFIX = 'AI.Prompts.';

    /** @var array|true[] */
    public const array LANGUAGES_REQUIRING_ONE_TOKEN_PER_CHAR = ['el' => true, 'jp' => true];

    /** @var Gpt3Tokenizer|null */
    public static ?Gpt3Tokenizer $tokenizer = null;

    /**
     * @param string $text
     * @param string $languageCode
     * @return int
     */
    public static function getTokenCountForStringToTranslate(string $text, string $languageCode): int
    {
        if (isset(self::LANGUAGES_REQUIRING_ONE_TOKEN_PER_CHAR[$languageCode])) {
            return strlen($text);
        }
        return self::getTokenCountForString($text);
    }

    /**
     * @param string $text
     * @return int
     */
    public static function getTokenCountForString(string $text): int
    {
        return self::getTokenizer()->count($text);
    }

    /**
     * @return Gpt3Tokenizer
     */
    public static function getTokenizer(): Gpt3Tokenizer
    {
        if (isset(self::$tokenizer)) {
            return self::$tokenizer;
        }
        $gpt3TokenizerConfig = new Gpt3TokenizerConfig();
        self::$tokenizer = new Gpt3Tokenizer($gpt3TokenizerConfig);
        return self::$tokenizer;
    }

    /**
     * Returns AIPrompt by name, loaded from markdown/text file via Config::get()
     *
     * The prompt name uses dots as path separators mapping to the config directory structure:
     * e.g. 'APP.TRANSLATIONS' loads from config/app/AI/Prompts/APP/TRANSLATIONS.md (or .txt)
     *
     * @param string $promptName
     * @return AIPrompt|null
     * @throws NotFoundException
     */
    public function getAIPromptByName(string $promptName): ?AIPrompt
    {
        $configPath = self::CONFIG_PROMPTS_PREFIX . $promptName;
        $promtText = Config::get($configPath);

        if ($promtText === null || !is_string($promtText)) {
            if ($this->throwErrors) {
                throw new NotFoundException("AIPrompt not found: $promptName");
            }
            return null;
        }

        $entityClassName = DDDService::instance()->getContainerServiceClassNameForClass(AIPrompt::class);
        /** @var AIPrompt $aiPrompt */
        $aiPrompt = new $entityClassName();
        $aiPrompt->name = $promptName;
        $aiPrompt->promtText = $promtText;

        return $aiPrompt;
    }
}
