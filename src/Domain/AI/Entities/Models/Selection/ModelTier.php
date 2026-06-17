<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Selection;

use DDD\Domain\AI\Entities\Models\AIModel;
use DDD\Infrastructure\Exceptions\BadRequestException;

/**
 * The capability tiers {@see \DDD\Domain\AI\Services\AIModelsService} can select within, ordered
 * CHEAP < STANDARD < PREMIUM. The canonical tier VALUES live on {@see AIModel}::AGENT_TIER_* (the per-model
 * `agentTier` attribute is set to those) — these constants alias them, so there is ONE source of truth for the
 * literal and this class only adds the ordering/validation helpers. String constants (not a native enum) to match
 * the AIModel::* const style. Not final — extensible per the project's visibility/extensibility rule.
 */
class ModelTier
{
    public const string CHEAP = AIModel::AGENT_TIER_CHEAP;

    public const string STANDARD = AIModel::AGENT_TIER_STANDARD;

    public const string PREMIUM = AIModel::AGENT_TIER_PREMIUM;

    /** @var array<string, int> Tier → rank (low = cheaper/weaker). The ordering used for step up/down later. */
    public const array ORDER = [
        self::CHEAP => 0,
        self::STANDARD => 1,
        self::PREMIUM => 2,
    ];

    /** @return string[] All tiers, weakest-first. */
    public static function getAll(): array
    {
        return array_keys(self::ORDER);
    }

    public static function isValid(string $tier): bool
    {
        return isset(self::ORDER[$tier]);
    }

    /**
     * @throws BadRequestException when $tier is not a known tier.
     */
    public static function rank(string $tier): int
    {
        if (!self::isValid($tier)) {
            throw new BadRequestException('Unknown model tier: ' . $tier);
        }
        return self::ORDER[$tier];
    }
}
