<?php

declare(strict_types=1);

namespace App\Components\Request\ParseRequestBody;

use App\Components\Request\ParseRequestBody\Parsers\FormParser;
use App\Components\Request\ParseRequestBody\Parsers\JsonParser;
use App\Components\Request\ParseRequestBody\Parsers\XmlParser;
use Psr\Http\Message\ServerRequestInterface;
use Webmozart\Assert\Assert;

class RequestBodyParser implements RequestBodyParserInterface
{
    private array $formats = [
        'json' => ['application/json', 'text/json'],
        'xml' => ['application/xml', 'text/xml'],
        'form' => ['application/x-www-form-urlencoded'],
    ];

    private array $parsers = [
        'json' => JsonParser::class,
        'xml' => XmlParser::class,
        'form' => FormParser::class,
    ];

    public function __construct(protected ServerRequestInterface $request)
    {
    }

    public function getParsedRequest(): ServerRequestInterface
    {
        $request = $this->request;
        $body = $request->getBody();
        $contentType = $request->getHeaderLine('Content-Type');

        $format = $this->getFormatFromContentType($contentType);
        if ($format === null) {
            return $request;
        }

        $parser = $this->getParserForFormat($format);
        if ($parser === null) {
            return $request;
        }

        $parsedBody = $parser->parse($body->getContents());

        $newRequest = clone $request;
        return $newRequest->withParsedBody($parsedBody);
    }

    private function getFormatFromContentType(string $contentType): ?string
    {
        $formats = $this->formats;

        foreach ($formats as $format => $mimeTypes) {
            foreach ($mimeTypes as $mimeType) {
                if (str_starts_with($contentType, $mimeType)) {
                    return $format;
                }
            }
        }

        return null;
    }

    private function getParserForFormat(string $format): ?ParserInterface
    {
        if (isset($this->parsers[$format])) {
            $parserClass = $this->parsers[$format];
            $parser = new $parserClass();
            Assert::isInstanceOf($parser, ParserInterface::class);

            return $parser;
        }

        return null;
    }
}
