<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Repo\Argus\Traits;

use DDD\Domain\AI\Exceptions\AiBudgetExceededException;
use DDD\Domain\Base\Repo\Argus\Utils\ArgusApiOperation;
use DDD\Domain\Common\Entities\Accounts\Account;
use DDD\Domain\Common\Entities\Money\MoneyAmount;
use DDD\Infrastructure\Exceptions\BadRequestException;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Infrastructure\Exceptions\NotFoundException;
use Doctrine\DBAL\Exception;
use InvalidArgumentException;
use ReflectionException;

trait ArgusAIImageModelTrait
{
    use ArgusAILanguageModelTrait;

    public const int DEFAULT_WIDTH = 1024;
    public const int DEFAULT_HEIGHT = 768;
    public const int DEFAULT_NUMBER_OF_IMAGES = 1;

    /** @var int Width of the image */
    public int $width = 1024;

    /** @var int Height of the image */
    public int $height = 768;

    /** @var int Number of images to generate */
    public int $numberOfImages =1 ;

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws ReflectionException
     * @throws InternalErrorException
     * @throws Exception
     */
    public function handleLoadResponseInternal(
        mixed &$callResponseData = null,
        ?ArgusApiOperation &$apiOperation = null,
    ): void {
        $responseObject = null;
        if (isset($callResponseData->status) && ($callResponseData->status == 200 || $callResponseData->status == 'OK') && ($callResponseData->data ?? null)) {
            $responseObject = $callResponseData->data;
        }
        if (!$responseObject) {
            $this->postProcessLoadResponse($callResponseData, false);
            return;
        }
        $loadResult = $responseObject->images ?? '';
        if (!$loadResult) {
            $this->postProcessLoadResponse($callResponseData, false);
            return;
        }
        $this->applyLoadResult($loadResult);

        if (isset($responseObject->cost)) {
            $this->updateCostsForUsage($responseObject->cost);
        }
        $this->postProcessLoadResponse($callResponseData, true);
    }

    /**
     * @return array|array[]|null
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws \Psr\Cache\InvalidArgumentException|AiBudgetExceededException
     */
    protected function getLoadPayloadInternal(): ?array
    {
        $aiModel = $this->getAIModel();

        if (!$this->isAIBudgetSufficientForOperation()) {
            throw new AiBudgetExceededException('AI Operation exceeds available AI Budget');
        }
        return [
            'body' => [
                'model' => $aiModel->externalId,
                'prompt' => $this->getAIPromptWithParametersApplied()->getPromtTextWithParametersApplied(),
            ]
        ];
    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws ReflectionException
     * @throws InternalErrorException
     */
    public function isAIBudgetSufficientForOperation(): bool
    {
        return true;
    }

    /**
     * Updates the costs based on Model and tokens
     * @param float $costUsd
     * @return void
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws ReflectionException
     * @throws Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function updateCostsForUsage(float $costUsd): void
    {
    }
}
