<?php

namespace CubeSystems\SoapClient\Client\Responses;

class ResponseStatusInfo
{
    private const STATUS_SUCCESS = 'S';
    private const STATUS_WARNING = 'W';
    private const STATUS_ERROR = 'E';

    private string $status;

    private string $code;

    private string $message;

    public function setStatus(string $status): ResponseStatusInfo
    {
        $this->status = $status;

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

    public function isWarning(): bool
    {
        return $this->status === self::STATUS_WARNING;
    }

    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
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
