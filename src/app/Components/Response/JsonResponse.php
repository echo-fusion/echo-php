<?php

declare(strict_types=1);

namespace App\Components\Response;

use App\Components\Response\Json\JsonException;
use GuzzleHttp\Psr7\Response;

class JsonResponse extends Response
{
    use InjectHeaderTrait;

    public const DEFAULT_JSON_FLAGS = JSON_HEX_TAG
        | JSON_HEX_APOS
        | JSON_HEX_AMP
        | JSON_HEX_QUOT
        | JSON_UNESCAPED_SLASHES;

    private $payload;

    public function __construct(
        $data,
        int $status = 200,
        array $headers = [],
        private int $encodingOptions = self::DEFAULT_JSON_FLAGS
    ) {
        if (is_object($data)) {
            $data = clone $data;
        }
        $this->payload = $data;

        parent::__construct(
            status: $status,
            headers: $this->injectHeader('content-type','application/json', $headers),
            body: $this->jsonEncode($data, $this->encodingOptions)
        );
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    private function jsonEncode(mixed $data, int $encodingOptions): string
    {
        if (is_resource($data)) {
            throw new JsonException('Cannot JSON encode resources');
        }

        try {
            return json_encode($data, $encodingOptions | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonException(sprintf('Unable to encode data to JSON: %s', $e->getMessage()), 0, $e);
        }
    }

    public function __toString(): string
    {
        return $this->getBody()->getContents();
    }
}
