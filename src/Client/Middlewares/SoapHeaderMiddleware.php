<?php

namespace CubeSystems\SoapClient\Client\Middlewares;

use CubeSystems\SoapClient\Client\Headers\Header;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Illuminate\Support\Collection;
use Psr\Http\Message\RequestInterface;
use Soap\Psr18Transport\Xml\XmlMessageManipulator;
use Soap\Xml\Builder\SoapHeaders;
use Soap\Xml\Manipulator\PrependSoapHeaders;
use VeeWee\Xml\Dom\Document;

use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;

class SoapHeaderMiddleware implements Plugin
{
    private $headers;
    public function __construct(Collection $headers)
    {
        $this->headers = $this->transformToSoapHeaders($headers);
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        $xmlEditor = new XmlMessageManipulator();

        $edited = $xmlEditor(
            $request,
            [$this, 'addHeadersToDocument']
        );

        return $next($edited);
    }

    private function transformToSoapHeaders(Collection $headers): callable
    {
        $collection = $headers->map(function (Header $header) {
            if ($header->hasNamespace()) {
                return namespaced_element(
                    $header->getNamespace(),
                    $header->getName(),
                    value($header->getValue())
                );
            }

            return element(
                $header->getName(),
                value($header->getValue())
            );
        });

        return children(...($collection->toArray()));
    }

    // has to be public to be passable as a callable
    public function addHeadersToDocument(Document $document): Document
    {
        $builder = new SoapHeaders($this->headers);
        $headers = $document->build($builder);
        $manipulation = new PrependSoapHeaders(...$headers);

        return $document->manipulate($manipulation);
    }
}
