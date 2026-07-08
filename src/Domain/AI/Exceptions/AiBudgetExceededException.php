<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Exceptions;

use DDD\Infrastructure\Exceptions\ForbiddenException;

/**
 * Ai Budget is exceeded.
 *
 * Carries the STANDARDIZED wire code {@see self::ERROR_CODE}: the public $errorCode property serializes into the
 * JSON error body (the ExceptionListener returns `$exception->toJSON()`), so every consumer — the chat frontends,
 * the agent loop, tool results, the media-manager generation UI — keys on the stable CODE instead of matching the
 * human-readable message string (which broke silently on any reword).
 */
class AiBudgetExceededException extends ForbiddenException
{
    /** @var string The stable wire code for "the account's AI budget is exhausted" — match on THIS, never on the message text. */
    public const string ERROR_CODE = 'AI_BUDGET_REACHED';

    /** @var string Machine-readable error code, serialized into the JSON error response alongside `error`. */
    public string $errorCode = self::ERROR_CODE;
}
