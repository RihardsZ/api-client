<?php

use CubeSystems\ApiClient\Client\Headers\Header;

it('gets what it sets via constructor', function() {
    $header = new Header('namespace', 'name', 'value');

    expect($header->getNamespace())->toBe('namespace')
        ->and($header->getName())->toBe('name')
        ->and($header->getValue())->toBe('value')
        ->and($header->hasNamespace())->toBeTrue();
});

it('gets what it sets via setters', function() {
    $header = new Header('', '', '');
    $header
        ->setNamespace('namespace')
        ->setName('name')
        ->setValue('value');

    expect($header->getNamespace())->toBe('namespace')
        ->and($header->getName())->toBe('name')
        ->and($header->getValue())->toBe('value')
        ->and($header->hasNamespace())->toBeTrue();

    $header->setNamespace('');

    expect($header->hasNamespace())->toBeFalse();
});
