<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Services;

use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Entities\Models\AIModels;
use DDD\Domain\AI\Entities\Models\Benchmarks\AIModelBenchmark;
use DDD\Domain\AI\Entities\Models\Benchmarks\AIModelBenchmarks;
use DDD\Domain\AI\Entities\Models\Settings\AIImageModelSetting;
use DDD\Domain\AI\Entities\Models\Settings\AILanguageModelSetting;
use DDD\Domain\AI\Entities\Models\Settings\AIModelAgenticUseCaseConfig;
use DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariant;
use DDD\Domain\AI\Entities\Models\Settings\RequestFeeVariants;
use DDD\Domain\AI\Entities\Models\Speed\AIModelSpeedMeasurement;
use DDD\Domain\AI\Entities\Models\Speed\AIModelSpeedMeasurements;
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
        $aiModel->supportsNativeToolCalling = (bool)($modelConfig['supportsNativeToolCalling'] ?? true);
        $aiModel->agentTier = isset($modelConfig['agentTier']) ? (string)$modelConfig['agentTier'] : null;
        $aiModel->agentEligible = (bool)($modelConfig['agentEligible'] ?? true);

        // Per-model AGENTIC-use-case config (reasoning effort, temperature) — applied only by the agentic egress,
        // riding with the ModelManager's dynamic selection. Absent → null (provider defaults). Scoped to the agent
        // loop; does not affect other use cases of the same model.
        $agenticConfig = $modelConfig['agenticUseCase'] ?? null;
        if (is_array($agenticConfig) && $agenticConfig !== []) {
            $aiModel->agenticUseCase = new AIModelAgenticUseCaseConfig(
                isset($agenticConfig['reasoningEffort']) ? (string)$agenticConfig['reasoningEffort'] : null,
                isset($agenticConfig['temperature']) ? (float)$agenticConfig['temperature'] : null,
            );
        }

        // Optional agentic-benchmark evidence, parallel to settings (BFCL, τ-bench, GAIA, …). Each entry is a
        // {benchmark, score 0–100, sourceUrl, asOf} datapoint; the blended agentic-intelligence score is computed
        // by AIModelBenchmarks::getAgenticIntelligenceScore(). Absent → null (unrated), no behaviour change.
        $benchmarksConfig = $modelConfig['benchmarks'] ?? null;
        if (is_array($benchmarksConfig) && $benchmarksConfig !== []) {
            $benchmarks = new AIModelBenchmarks();
            foreach ($benchmarksConfig as $entry) {
                if (!is_array($entry) || !isset($entry['benchmark'])) {
                    continue;
                }
                // Assign to a variable first — ObjectSet::add() takes its argument by reference, so passing a
                // `new ...` expression directly raises "Only variables should be passed by reference" (mirrors the
                // RequestFeeVariants hydration pattern below).
                $benchmark = new AIModelBenchmark(
                    (string)$entry['benchmark'],
                    (float)($entry['score'] ?? 0.0),
                    isset($entry['sourceUrl']) ? (string)$entry['sourceUrl'] : null,
                    isset($entry['asOf']) ? (string)$entry['asOf'] : null,
                    (bool)($entry['official'] ?? true),
                );
                $benchmarks->add($benchmark);
            }
            $aiModel->benchmarks = $benchmarks;
        }

        // Optional output-speed evidence, parallel to settings/benchmarks. Each entry is a
        // {source, tokensPerSecond, timeToFirstTokenMs, sourceUrl, asOf} datapoint; the canonical (OpenRouter)
        // tokens/sec and the normalised speed score are exposed via AIModelSpeedMeasurements. Absent → null.
        $speedConfig = $modelConfig['speed'] ?? null;
        if (is_array($speedConfig) && $speedConfig !== []) {
            $speed = new AIModelSpeedMeasurements();
            foreach ($speedConfig as $entry) {
                if (!is_array($entry) || !isset($entry['source'])) {
                    continue;
                }
                // Variable first — ObjectSet::add() is by-reference (see the benchmark block above).
                $measurement = new AIModelSpeedMeasurement(
                    (string)$entry['source'],
                    (float)($entry['tokensPerSecond'] ?? 0.0),
                    isset($entry['timeToFirstTokenMs']) ? (int)$entry['timeToFirstTokenMs'] : null,
                    isset($entry['sourceUrl']) ? (string)$entry['sourceUrl'] : null,
                    isset($entry['asOf']) ? (string)$entry['asOf'] : null,
                );
                $speed->add($measurement);
            }
            $aiModel->speed = $speed;
        }

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

            if (isset($settings['costsPer1000CachedInputTokensInUSD'])) {
                $aiModelSetting->costsPer1000KCachedInputTokens = new MoneyAmount(
                    (float)$settings['costsPer1000CachedInputTokensInUSD'], 'USD'
                );
            }

            if (isset($settings['costsPerWebSearchCallInUSD'])) {
                $aiModelSetting->costsPerWebSearchCall = new MoneyAmount(
                    (float)$settings['costsPerWebSearchCallInUSD'], 'USD'
                );
            }

            $costsPerRequestInUSDByVariant = $settings['costsPerRequestInUSDByVariant'] ?? null;
            if (is_array($costsPerRequestInUSDByVariant) && $costsPerRequestInUSDByVariant !== []) {
                $aiModelSetting->costsPerRequestVariants = new RequestFeeVariants();
                foreach ($costsPerRequestInUSDByVariant as $variant => $amount) {
                    $feeVariant = new RequestFeeVariant(
                        (string)$variant,
                        new MoneyAmount((float)$amount, 'USD')
                    );
                    $aiModelSetting->costsPerRequestVariants->add($feeVariant);
                }
            }

            $aiModel->settings = $aiModelSetting;
        } elseif ($aiModel->type === AIModel::TYPE_IMAGE) {
            $settings = $modelConfig['settings'] ?? [];
            $aiModelSetting = new AIImageModelSetting();

            if (isset($settings['costsPerImageInUSD'])) {
                $aiModelSetting->costsPerImageInUSD = new MoneyAmount(
                    (float)$settings['costsPerImageInUSD'], 'USD'
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
