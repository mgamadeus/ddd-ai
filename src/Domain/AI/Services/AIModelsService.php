<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Services;

use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Entities\Models\AIModels;
use DDD\Domain\AI\Entities\Models\Settings\AILanguageModelSetting;
use DDD\Domain\Common\Entities\Money\MoneyAmount;
use DDD\Infrastructure\Exceptions\BadRequestException;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Infrastructure\Exceptions\NotFoundException;
use DDD\Infrastructure\Libs\Config;
use DDD\Infrastructure\Services\DDDService;
use DDD\Infrastructure\Services\Service;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

class AIModelsService extends Service
{
    public function getAIModels(): AIModels
    {
        $aiModelsConfig = Config::get('AI.models');
        $entiySetClassName = DDDService::instance()->getContainerServiceClassNameForClass(AIModels::class);
        /** @var AIModels $aiModels */
        $aiModels = new $entiySetClassName();

        foreach ($aiModelsConfig as $modelKey => $modelConfig) {
            $model = $this->createAIModelFromConfig($modelKey, $modelConfig);
            $aiModels->add($model);
        }

        return $aiModels;
    }

    protected function createAIModelFromConfig(string $modelKey, array $modelConfig): AIModel
    {
        $entiyClassName = DDDService::instance()->getContainerServiceClassNameForClass(AIModel::class);
        /** @var AIModel $aiModel */
        $aiModel = new $entiyClassName();
        $aiModel->type = $modelConfig['type'];
        $aiModel->vendor = $modelConfig['vendor'];
        $aiModel->name = $modelKey;
        $aiModel->externalId = $modelConfig['externalId'];
        $aiModel->openRouterExternalId = $modelConfig['openRouterExternalId'] ?? null;
        $aiModel->description = $modelConfig['description'] ?? null;
        $aiModel->isReasoningModel = (bool)($modelConfig['isReasoningModel'] ?? false);
        $aiModel->hasVisionCapabilities = (bool)($modelConfig['hasVisionCapabilities'] ?? false);

        if ($aiModel->type === AIModel::TYPE_LANGUAGE) {
            $settings = $modelConfig['settings'] ?? [];
            $aiModelSetting = new AILanguageModelSetting();

            $aiModelSetting->maxTokens = (int)($settings['maxTokens'] ?? 0);
            $aiModelSetting->maxInputTokens = (int)($settings['maxInputTokens'] ?? $aiModelSetting->maxTokens);
            $aiModelSetting->maxOutputTokens = (int)($settings['maxOutputTokens'] ?? 0);
            $aiModelSetting->maxPracticallyUsableInputTokens = (int)($settings['maxPracticallyUsableInputTokens'] ?? $aiModelSetting->maxInputTokens);

            $aiModelSetting->costsPer1000KInputTokens = new MoneyAmount(
                (float)($settings['costsPer1000InputTokensInUSD'] ?? 0.0), 'USD'
            );
            $aiModelSetting->costsPer1000KOutputTokens = new MoneyAmount(
                (float)($settings['costsPer1000OuputTokensInUSD'] ?? 0.0), 'USD'
            );

            if (isset($settings['inputTierThresholdTokens'])) {
                $aiModelSetting->inputTierThresholdTokens = (int)$settings['inputTierThresholdTokens'];
            }

            if (isset($settings['costsPer1000InputTokensInUSDAboveThreshold'])) {
                $aiModelSetting->costsPer1000InputTokensAboveThreshold = new MoneyAmount(
                    (float)$settings['costsPer1000InputTokensInUSDAboveThreshold'], 'USD'
                );
            }

            if (isset($settings['costsPer1000OutputTokensInUSDAboveThreshold'])) {
                $aiModelSetting->costsPer1000OutputTokensAboveThreshold = new MoneyAmount(
                    (float)$settings['costsPer1000OutputTokensInUSDAboveThreshold'], 'USD'
                );
            }

            $aiModel->settings = $aiModelSetting;
        }

        return $aiModel;
    }

    /**
     * Returns AIModel by name
     * @param string $aiModelName
     * @return AIModel|null
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function getAIModelByName(string $aiModelName): ?AIModel
    {
        $configs = Config::get('AI.models');
        $aiModelConfig = $configs[$aiModelName] ?? null;
        $aiModel = $aiModelConfig ? $this->createAIModelFromConfig($aiModelName, $aiModelConfig) : null;

        if ($this->throwErrors && !$aiModel) {
            throw new NotFoundException('AiModel not found');
        }

        return $aiModel;
    }
}
