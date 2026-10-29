<?php

declare(strict_types=1);

namespace DDD\Tests\Domain\AI\Services;

use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Entities\Models\Selection\ModelManagerPolicy;
use DDD\Domain\AI\Entities\Models\Selection\ModelSelectionContext;
use DDD\Domain\AI\Entities\Models\Selection\ModelSelectionDecision;
use DDD\Domain\AI\Services\AIModelsService;
use DDD\Infrastructure\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * AIModelsService is a shared container instance: a model selection must not leave `throwErrors` switched on, or every
 * later getAIModelByName() of an unknown or retired name throws instead of returning null, depending on what ran
 * first in the process.
 */
class AIModelsServiceThrowErrorsTest extends TestCase
{
    protected function setUp(): void
    {
        AIModelsService::clearSelectionCache();
    }

    protected function tearDown(): void
    {
        AIModelsService::clearSelectionCache();
    }

    protected function createSelectionContext(): ModelSelectionContext
    {
        return new ModelSelectionContext(new ModelManagerPolicy(AIModel::AGENT_TIER_STANDARD));
    }

    public function testASelectionLeavesTheFlagOffAndUnknownNamesReturnNull(): void
    {
        $aiModelsService = new AIModelsService();
        $aiModelsService->throwErrors = false;

        $this->assertNotNull($aiModelsService->selectModel($this->createSelectionContext()));

        $this->assertFalse($aiModelsService->throwErrors);
        $this->assertNull($aiModelsService->getAIModelByName('no-such-model'));
    }

    public function testASelectionKeepsAFlagThatWasOn(): void
    {
        $aiModelsService = new AIModelsService();
        $aiModelsService->throwErrors = true;

        $aiModelsService->selectModel($this->createSelectionContext());

        $this->assertTrue($aiModelsService->throwErrors);
    }

    public function testTheParetoFrontierLeavesTheFlagUnchanged(): void
    {
        $aiModelsService = new AIModelsService();
        $aiModelsService->throwErrors = false;

        $this->assertGreaterThan(0, $aiModelsService->getAgenticParetoFrontier()->count());

        $this->assertFalse($aiModelsService->throwErrors);
    }

    public function testASelectedModelMissingFromTheCatalogThrowsAndRestoresTheFlag(): void
    {
        $aiModelsService = new class extends AIModelsService {
            public function selectCandidate(ModelSelectionContext $context, array $candidates): ModelSelectionDecision
            {
                return new ModelSelectionDecision(
                    'no-such-model', AIModel::AGENT_TIER_STANDARD, null, 0.0, null, false, 0, 0, 'cost', 'test'
                );
            }
        };
        $aiModelsService->throwErrors = false;

        try {
            $aiModelsService->selectModel($this->createSelectionContext());
            $this->fail('A selected model missing from the catalog must throw.');
        } catch (NotFoundException) {
        }

        $this->assertFalse($aiModelsService->throwErrors);
    }
}
