<?php

use CubeSystems\ApiClient\Client\Headers\Header;
use CubeSystems\ApiClient\Client\Middlewares\SoapHeaderMiddleware;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xml_string;

it('adds namespaced headers to document', function () {

    $headers = [
        new Header(
            'http://schemas.xmlsoap.org/soap/envelope',
            'Header1',
            'Value1'
        ),
        new Header(
            'http://schemas.xmlsoap.org/soap/envelope',
            'Header2',
            'Value2'
        ),
    ];

    $middleware = new SoapHeaderMiddleware(collect($headers));

    $xml =
"<?xml version='1.0' encoding='UTF-8'?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope'>
    <soap:Body>
    </soap:Body>
</soap:Envelope>";
    $document = Document::fromXmlString($xml);
    $document = $middleware->addHeadersToDocument($document);
    $headerString = xml_string()($document->xpath()->query('/soap:Envelope/soap:Header')->item(0));


    expect($headerString)->toBe(
        "<soap:Header><soap:Header1>Value1</soap:Header1><soap:Header2>Value2</soap:Header2></soap:Header>"
    );
});

it('adds non-namespaced headers to document', function () {

    $headers = [
        new Header(
            '',
            'Header1',
            'Value1'
        ),
        new Header(
            '',
            'Header2',
            'Value2'
        ),
    ];

    $middleware = new SoapHeaderMiddleware(collect($headers));

    $xml =
        "<?xml version='1.0' encoding='UTF-8'?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope'>
    <soap:Body>
    </soap:Body>
</soap:Envelope>";
    $document = Document::fromXmlString($xml);
    $document = $middleware->addHeadersToDocument($document);
    $headerString = xml_string()($document->xpath()->query('/soap:Envelope/soap:Header')->item(0));

    expect($headerString)->toBe(
        "<soap:Header><Header1>Value1</Header1><Header2>Value2</Header2></soap:Header>"
    );
});

it('adds mix of namespaced and non-namespaced headers to document', function () {

    $headers = [
        new Header(
            'http://schemas.xmlsoap.org/soap/envelope',
            'Header1',
            'Value1'
        ),
        new Header(
            '',
            'Header2',
            'Value2'
        ),
        new Header(
            'http://schemas.xmlsoap.org/soap/envelope',
            'Header3',
            'Value3'
        ),
        new Header(
            '',
            'Header4',
            'Value4'
        ),
    ];

    $middleware = new SoapHeaderMiddleware(collect($headers));

    $xml =
        "<?xml version='1.0' encoding='UTF-8'?>
<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope'>
    <soap:Body>
    </soap:Body>
</soap:Envelope>";
    $document = Document::fromXmlString($xml);
    $document = $middleware->addHeadersToDocument($document);
    $headerString = xml_string()($document->xpath()->query('/soap:Envelope/soap:Header')->item(0));

    expect($headerString)->toBe(
        "<soap:Header><soap:Header1>Value1</soap:Header1><Header2>Value2</Header2><soap:Header3>Value3</soap:Header3><Header4>Value4</Header4></soap:Header>"
    );
});
