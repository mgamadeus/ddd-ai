<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Entities\Models\Speed;

use DDD\Domain\Base\Entities\ValueObject;

/**
 * A p50/p75/p90/p99 percentile bundle for ONE speed metric (throughput in tokens/sec, or latency/time-to-first-token
 * in milliseconds), as published per OpenRouter model+provider over the rolling 30-minute live-traffic window.
 *
 * Held (up to four times) inside {@see AIModelSpeedMeasurement} — once each for top/avg × throughput/latency — so the
 * {@see AIModelSpeedMeasurements}::SOURCE_OPENROUTER_PROVIDERS measurement carries the full provider-resolved
 * distribution, not a single blended number. A small typed VO rather than a bare array (mirrors the
 * AIModelSpeedMeasurement / AIModelBenchmark precedent: "arrays are not a substitute for VOs").
 */
class SpeedPercentiles extends ValueObject
{
    /** @var float|null 50th-percentile value (the headline median) over the measurement window. */
    public ?float $p50 = null;

    /** @var float|null 75th-percentile value. */
    public ?float $p75 = null;

    /** @var float|null 90th-percentile value. */
    public ?float $p90 = null;

    /** @var float|null 99th-percentile value (the slow/fast tail). */
    public ?float $p99 = null;

    public function __construct(
        ?float $p50 = null,
        ?float $p75 = null,
        ?float $p90 = null,
        ?float $p99 = null
    ) {
        $this->p50 = $p50;
        $this->p75 = $p75;
        $this->p90 = $p90;
        $this->p99 = $p99;
        parent::__construct();
    }

    /**
     * Build from a config array {p50, p75, p90, p99} (any key may be absent → null), or null when the array is empty
     * or not an array. Used by AIModelsService when hydrating the SOURCE_OPENROUTER_PROVIDERS speed measurement.
     */
    public static function fromConfigArray(mixed $config): ?self
    {
        if (!is_array($config) || $config === []) {
            return null;
        }
        return new self(
            isset($config['p50']) ? (float)$config['p50'] : null,
            isset($config['p75']) ? (float)$config['p75'] : null,
            isset($config['p90']) ? (float)$config['p90'] : null,
            isset($config['p99']) ? (float)$config['p99'] : null,
        );
    }
}
