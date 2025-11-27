<?php

namespace App\Services\Integration\Exceptions;

use RuntimeException;

class IntegrationException extends RuntimeException
{
    public static function fromThrowable(string $message, \Throwable $throwable): self
    {
        return new self($message . ' ' . $throwable->getMessage(), $throwable->getCode(), $throwable);
    }
}

