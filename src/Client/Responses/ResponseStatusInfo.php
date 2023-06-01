<?php

namespace CubeSystems\ApiClient\Client\Responses;

class ResponseStatusInfo
{
    private const STATUS_SUCCESS = 'S';
    private const STATUS_ERROR = 'E';
    private const STATUS_TECHNICAL_ERROR = 'T';

    private string $status;

    private string $code;

    private string $message;

    public function setStatus(string $status): ResponseStatusInfo
    {
        $this->status = $status;

        return $this;
    }

    public function setTechnicalErrorStatus(): ResponseStatusInfo
    {
        $this->status = self::STATUS_TECHNICAL_ERROR;

        return $this;
    }

    public function setErrorStatus(): ResponseStatusInfo
    {
        $this->status = self::STATUS_ERROR;

        return $this;
    }

    public function setSuccessStatus(): ResponseStatusInfo
    {
        $this->status = self::STATUS_SUCCESS;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * If true, this is considered a successful method call, but the response
     * contains an error. i.e. this is correct response for given payload
     * but it does not contain expected data.
     */
    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }

    /**
     * If true, this is considered a failed method call, i.e. this is not the
     * correct response for given payload. Possible reasons for this include endpoint
     * downtime, connectivity issues, malformed payloads or server internal errors.
     */
    public function isTechnicalError(): bool
    {
        return $this->status === self::STATUS_TECHNICAL_ERROR;
    }

    public function setCode(string $code): ResponseStatusInfo
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setMessage(string $message): ResponseStatusInfo
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
