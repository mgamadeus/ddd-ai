<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Settings;

use DDD\Domain\Common\Entities\Money\MoneyAmount;

/**
 * Settings for image generation models (e.g. FALAI, DALL-E, FluxAI, Gemini image models).
 *
 * Image models price per generated image rather than per token, so they carry a
 * single MoneyAmount field instead of token-rate triplets. Width/height/numberOfImages
 * are runtime parameters on the call (see ArgusAIImageModelTrait), not settings.
 */
class AIImageModelSetting extends AIModelSetting
{
    /** @var MoneyAmount|null Cost per generated image */
    public ?MoneyAmount $costsPerImageInUSD = null;
}
