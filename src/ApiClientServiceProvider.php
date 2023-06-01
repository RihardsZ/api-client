<?php

namespace CubeSystems\ApiClient;

use Barryvdh\Debugbar\Facades\Debugbar;
use CodeDredd\Soap\SoapFactory;
use CubeSystems\ApiClient\Client\ApiClient;
use CubeSystems\ApiClient\Debugbar\ApiCollector;
use CubeSystems\ApiClient\Listeners\ApiDebugbarSubscriber;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ApiClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('api-client');
    }

    public function boot(): void
    {
        parent::boot();

        SoapFactory::useClientClass(ApiClient::class);

        if (config('debugbar.enabled') && config('debugbar.collectors.api')) {
            $this->addApiCollectorToDebugbar();
        }
    }

    private function addApiCollectorToDebugbar(): void
    {
        $collector = new ApiCollector();

        Debugbar::addCollector($collector);

        Event::subscribe(ApiDebugbarSubscriber::class);
    }
}
