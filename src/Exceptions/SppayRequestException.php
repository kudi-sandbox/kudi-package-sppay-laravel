<?php

namespace Mboateng\Sppay\Exceptions;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;
use Throwable;

class SppayRequestException extends RuntimeException
{
    public function __construct(
        string $message,
        int $code = 0,
        ?Throwable $previous = null,
        protected ?string $responseBody = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public static function fromGuzzle(GuzzleException $e): self
    {
        $body = null;
        $code = 0;
        if ($e instanceof RequestException && $e->hasResponse()) {
            $code = (int) $e->getResponse()->getStatusCode();
            $body = (string) $e->getResponse()->getBody();
        }

        return new self($e->getMessage(), $code, $e, $body);
    }
}
