<?php

use CubeSystems\ApiClient\Client\Responses\ResponseStatusInfo;

it('gets what it sets', function () {
    $info = new ResponseStatusInfo();
    $info
        ->setStatus('status')
        ->setCode('code')
        ->setMessage('message');

    expect($info->getStatus())->toBe('status')
        ->and($info->getCode())->toBe('code')
        ->and($info->getMessage())->toBe('message');
});

it('has working shorthand status setters', function () {
    $info = new ResponseStatusInfo();
    $info->setSuccessStatus();

    expect($info->getStatus())->toBe('S')
        ->and($info->isSuccess())->toBeTrue()
        ->and($info->isError())->toBeFalse()
        ->and($info->isTechnicalError())->toBeFalse();

    $info->setErrorStatus();

    expect($info->getStatus())->toBe('E')
        ->and($info->isSuccess())->toBeFalse()
        ->and($info->isError())->toBeTrue()
        ->and($info->isTechnicalError())->toBeFalse();

    $info->setTechnicalErrorStatus();

    expect($info->getStatus())->toBe('T')
        ->and($info->isSuccess())->toBeFalse()
        ->and($info->isError())->toBeFalse()
        ->and($info->isTechnicalError())->toBeTrue();
});
