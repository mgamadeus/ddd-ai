<?php

declare(strict_types=1);

namespace DDD\Tests\Domain\AI\Entities\Models;

use DateTimeImmutable;
use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Domain\AI\Services\AIModelsService;
use PHPUnit\Framework\TestCase;

class GeminiFlashCatalogTest extends TestCase
{
    /**
     * @var string Google's introductory pricing for the 3.x Flash models runs out on this date; from then on the
     * list price below applies. {@see self::testTheGeminiIntroductoryPriceWindowHasNotClosed()} turns the deadline
     * into a failing test instead of a silent under-booking.
     */
    protected const string INTRODUCTORY_PRICE_VALID_UNTIL = '2026-12-31';

    /** @var array The list prices per 1000 tokens that apply once the introductory window closes */
    protected const array LIST_PRICES_FROM_2027 = [
        'costsPer1000InputTokensInUSD' => 0.0015,
        'costsPer1000OuputTokensInUSD' => 0.0075,
        'costsPer1000CachedInputTokensInUSD' => 0.00015,
    ];

    /** @var string[] The models the introductory window covers */
    protected const array INTRODUCTORY_PRICED_MODELS = [
        AIModel::MODEL_GOOGLE_GEMINI_3_6_FLASH,
        AIModel::MODEL_GOOGLE_GEMINI_3_8_FLASH,
    ];

    protected array $catalog;

    protected function setUp(): void
    {
        $this->catalog = dddAiModelCatalog();
    }

    public function testGemini38FlashIsInTheCatalogAsPublished(): void
    {
        $row = $this->catalog[AIModel::MODEL_GOOGLE_GEMINI_3_8_FLASH] ?? null;
        $this->assertIsArray($row, 'GOOGLE.GEMINI_3_8_FLASH is missing');
        $this->assertSame('gemini-3.8-flash', $row['externalId']);
        $this->assertSame('google/gemini-3.8-flash', $row['openRouterExternalId']);
        $this->assertSame(AIModel::VENDOR_GOOGLE, $row['vendor']);
        $this->assertSame(1048576, $row['settings']['maxInputTokens']);
        $this->assertSame(65536, $row['settings']['maxOutputTokens']);
        $this->assertSame(1048576 + 65536, $row['settings']['maxTokens'], 'the total budget is input + output');
        $this->assertTrue($row['isReasoningModel'] && $row['hasVisionCapabilities']);
        $this->assertSame(0.014, $row['settings']['costsPerWebSearchCallInUSD'], 'search grounding is billed per query');
    }

    public function testTheNewEntryChangesNoTierPickOrDefault(): void
    {
        // it exists so callers can NAME the model; a tier or an agentic default was not asked for
        $row = $this->catalog[AIModel::MODEL_GOOGLE_GEMINI_3_8_FLASH];
        $this->assertArrayNotHasKey('agentTier', $row);
        $this->assertArrayNotHasKey('agenticUseCase', $row);
        $this->assertSame([], $row['speed'], 'no speed was measured');
        $this->assertSame([], $row['benchmarks'], 'no benchmark was measured');
    }

    public function testTheIntroductoryPriceIsBookedWhileItApplies(): void
    {
        foreach (self::INTRODUCTORY_PRICED_MODELS as $modelName) {
            $settings = $this->catalog[$modelName]['settings'];
            $this->assertSame(0.00075, $settings['costsPer1000InputTokensInUSD'], "$modelName input");
            $this->assertSame(0.00375, $settings['costsPer1000OuputTokensInUSD'], "$modelName output");
            $this->assertSame(0.000075, $settings['costsPer1000CachedInputTokensInUSD'], "$modelName cached");
        }
    }

    public function testTheGeminiIntroductoryPriceWindowHasNotClosed(): void
    {
        $windowClosed = new DateTimeImmutable() > new DateTimeImmutable(self::INTRODUCTORY_PRICE_VALID_UNTIL . ' 23:59:59');
        if (!$windowClosed) {
            $this->assertTrue(true, 'the introductory window is still open — nothing to do');
            return;
        }
        // Deliberate deadline: from here on the catalog under-books every Gemini 3.x Flash call by half.
        foreach (self::INTRODUCTORY_PRICED_MODELS as $modelName) {
            foreach (self::LIST_PRICES_FROM_2027 as $key => $listPrice) {
                $this->assertSame(
                    $listPrice,
                    $this->catalog[$modelName]['settings'][$key],
                    "Google's introductory pricing ended on " . self::INTRODUCTORY_PRICE_VALID_UNTIL
                    . " — set $modelName $key to the list price $listPrice (and re-check the pricing page, the"
                    . ' window may have been extended).'
                );
            }
        }
    }

    public function testTheShutDownFlashLitePreviewIdsAreGone(): void
    {
        // the preview was shut down 2026-05-25; a call with the preview id fails
        $row = $this->catalog[AIModel::MODEL_GOOGLE_GEMINI_3_1_FLASH_LITE];
        $this->assertSame('gemini-3.1-flash-lite', $row['externalId']);
        $this->assertSame('google/gemini-3.1-flash-lite', $row['openRouterExternalId']);
        $this->assertStringNotContainsString('preview', $row['externalId']);
    }

    public function testEveryGeminiFlashEntryPricesACachedRead(): void
    {
        // a missing cached price books a cached read at the full input rate
        foreach ($this->catalog as $modelName => $row) {
            if (($row['vendor'] ?? null) !== AIModel::VENDOR_GOOGLE || ($row['type'] ?? null) !== AIModel::TYPE_LANGUAGE) {
                continue;
            }
            $this->assertArrayHasKey(
                'costsPer1000CachedInputTokensInUSD',
                $row['settings'],
                "$modelName has no cached-input price"
            );
        }
    }

    public function testTheNewModelResolvesThroughTheService(): void
    {
        $model = (new AIModelsService())->getAIModelByName(AIModel::MODEL_GOOGLE_GEMINI_3_8_FLASH);
        $this->assertNotNull($model);
        $this->assertSame('gemini-3.8-flash', $model->externalId);
        $this->assertSame('google/gemini-3.8-flash', $model->openRouterExternalId);
    }
}
