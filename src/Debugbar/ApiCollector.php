<?php

namespace CubeSystems\ApiClient\Debugbar;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class ApiCollector extends DataCollector implements Renderable, AssetProvider
{
    /** @var array<DebugbarEntry> */
    protected array $apiCalls = [];

    protected int $numberOfServiceCalls = 0;

    protected int $numberOfCacheRetrievals = 0;

    public function addEntry(DebugbarEntry $entry): void
    {
        $this->apiCalls[] = $entry;

        if ($entry->isCached()) {
            $this->numberOfCacheRetrievals++;
        }

        if (!$entry->isCached()) {
            $this->numberOfServiceCalls++;
        }
    }

    public function getName(): string
    {
        return 'api';
    }

    public function collect(): array
    {
        $data = array_map(static function (DebugbarEntry $entry) {
            return $entry->toArray();
        }, $this->apiCalls);

        return [
            'entries' => $data,
            'badge' => "{$this->numberOfCacheRetrievals} | {$this->numberOfServiceCalls}"
        ];
    }

    public function getWidgets(): array
    {
        return [
            'api' => [
                "widget" => "PhpDebugBar.Widgets.ApiWidget",
                "map" => "api.entries",
                "default" => "[]"
            ],
            'api:badge' => [
                'map' => 'api.badge',
                'default' => '0 | 0'
            ]
        ];
    }

    public function getAssets(): array
    {
        return [
            'css' => __DIR__ . '/../../resources/css/ApiCollector/widget.css',
            'js' => __DIR__ . '/../../resources/js/ApiCollector/widget.js'
        ];
    }
}
