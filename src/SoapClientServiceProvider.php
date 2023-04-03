<?php

namespace CubeSystems\SoapClient;

use Barryvdh\Debugbar\Facades\Debugbar;
use CodeDredd\Soap\SoapFactory;
use CubeSystems\SoapClient\Client\SoapClient;
use CubeSystems\SoapClient\Commands\SoapClientCommand;
use CubeSystems\SoapClient\Debugbar\SoapCollector;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SoapClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('soap-client')
            ->hasViews()
            ->hasMigration('create_soap_client_table')
            ->hasCommand(SoapClientCommand::class);
    }

    public function boot(): void
    {
        parent::boot();

        SoapFactory::useClientClass(SoapClient::class);

        if (config('debugbar.enabled') && config('debugbar.collectors.soap')) {
            $this->addSoapCollectorToDebugbar();
        }
    }

    private function addSoapCollectorToDebugbar(): void
    {
        $collector = new SoapCollector();

        Debugbar::addCollector($collector);
    }
}
