<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Exceptions;

use DDD\Infrastructure\Exceptions\ForbiddenException;

/**
 * Ai Budget is exceeded
 */
class AiBudgetExceededException extends ForbiddenException
{
}