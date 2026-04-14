<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Repo\Argus\Traits;

use DDD\Domain\AI\Services\AIPromptsService;
use DDD\Domain\Base\Repo\Argus\Traits\ArgusTrait;
use DDD\Domain\Base\Repo\Argus\Utils\ArgusApiOperation;
use DDD\Infrastructure\Exceptions\BadRequestException;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Infrastructure\Exceptions\NotFoundException;
use DDD\Infrastructure\Libs\Config;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

trait ArgusAITextGenerationTrait
{
    use ArgusTrait;

    /**
     * @return array[]|null
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    protected function getLoadPayload(): ?array
    {
        $this->initializeLoadDependencies();
        $userContent = $this->getUserContent();
        $this->consumedTokens += $this->aiPrompt->getTokenCountWithParametersApplied(
            ) + AIPromptsService::getTokenCountForString($userContent);

        return [
            'body' => [
                'api_key' =>  Config::getEnv('API_OPENAI_KEY'),
                'model' => $this->aiModel->externalId,
                'max_tokens' => (int)($this->aiModel->settings->maxTokens / 2),
                'messages' => [
                    ['role' => 'system', 'content' => $this->aiPrompt->getPromtTextWithParametersApplied()],
                    ['role' => 'user', 'content' => $userContent]
                ]
            ]
        ];
    }

    /**
     * @param mixed|null $callResponseData
     * @param ArgusApiOperation|null $apiOperation
     * @return void
     * @throws BadRequestException
     */
    public function handleLoadResponse(
        mixed &$callResponseData = null,
        ?ArgusApiOperation &$apiOperation = null
    ): void {
        if (!isset($callResponseData->status, $callResponseData->data) || ($callResponseData->status !== 200 && $callResponseData->status !== 'OK')) {
            $this->postProcessLoadResponse($callResponseData, false);
            throw new BadRequestException('Something went wrong generating the response');
        }
        $responseObject = $callResponseData->data;
        $aiGeneratedResponse = $responseObject->choices[0]?->message?->content ?? null;
        if (!$aiGeneratedResponse) {
            throw new BadRequestException('Something went wrong generating the reply');
        }

        $this->incrementAIOperationCosts($aiGeneratedResponse);
        $this->setAiGeneratedResponse($aiGeneratedResponse);

        $this->postProcessLoadResponse($callResponseData);
    }
}
