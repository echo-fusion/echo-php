<?php

declare(strict_types=1);

namespace App\Components\Response;

use function array_keys;
use function array_reduce;
use function strtolower;

trait InjectHeaderTrait
{
    private function injectHeader(string $key,string $value,  array $headers): array
    {
        $hasContentType = array_reduce(
            array_keys($headers),
            static fn ($carry, $item) => $carry ?: strtolower($item) === $key,
            false
        );

        if (! $hasContentType) {
            $headers[$key] = [$value];
        }

        return $headers;
    }
}
